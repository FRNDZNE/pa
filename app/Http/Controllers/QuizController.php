<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\StudentScore;
use App\Models\StudentDifficultyScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Tampilkan daftar semua quiz yang tersedia untuk student.
     */
    public function index()
    {
        $student = Auth::user()->student;

        // Ambil semua lesson yang memiliki soal (questions)
        $lessons = Lesson::whereHas('questions')
            ->with(['studentScore' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->withCount('questions')
            ->latest()
            ->paginate(12);

        return view('quiz.index', compact('lessons'));
    }

    /**
     * Tampilkan quiz untuk student.
     */
    public function show(Lesson $lesson)
    {
        $student = Auth::user()->student;

        // Cek apakah student sudah pernah submit quiz ini
        $alreadySubmitted = StudentScore::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->exists();

        // Ambil semua soal dari lesson ini beserta jawaban & difficulty
        $questions = $lesson->materials()
            ->with(['questions.answers'])
            ->get()
            ->flatMap(function ($material) {
                return $material->questions->map(function ($question) use ($material) {
                    return [
                        'question'   => $question,
                        'answers'    => $question->answers,
                        'difficulty' => $material->difficulty,
                    ];
                });
            });

        return view('quiz.show', compact('lesson', 'questions', 'alreadySubmitted'));
    }

    /**
     * Submit jawaban quiz & hitung skor per difficulty.
     */
    public function submit(Lesson $lesson, Request $request)
    {
        $student = Auth::user()->student;

        // Cek apakah sudah pernah submit
        $alreadySubmitted = StudentScore::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', 'Kamu sudah pernah mengerjakan quiz ini.');
        }

        $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required|integer|exists:question_answers,id',
        ]);

        $submittedAnswers = $request->input('answers'); // [question_id => answer_id]

        // [OPTIMASI RULE-BASED] Hindari N+1 Query: Tarik data pertanyaan, material, dan opsi kunci jawaban sekaligus
        $questionIds = array_keys($submittedAnswers);
        $questions = Question::with(['material', 'answers'])->whereIn('id', $questionIds)->get()->keyBy('id');

        DB::transaction(function () use ($student, $lesson, $submittedAnswers, $questions) {

            // ── 1. Simpan student_answers & logika komparasi Poin (Scoring Rule-Based) ────
            $difficultyStats = []; // ['mudah' => ['total' => 0, 'correct' => 0], ...]
            $totalCorrect = 0;
            $totalQuestions = 0;

            foreach ($submittedAnswers as $questionId => $answerId) {
                // Catat aktivitas kebenaran rekam jejak
                StudentAnswer::create([
                    'question_id' => $questionId,
                    'answer_id'   => $answerId,
                    'student_id'  => $student->id,
                ]);

                // Ambil info pertanyaan dari memori Collection
                $question = $questions->get($questionId);
                if (!$question) continue;

                $difficulty = $question->material->difficulty ?? 'mudah';

                // Inisialisasi stats per tingkat kesulitan
                if (!isset($difficultyStats[$difficulty])) {
                    $difficultyStats[$difficulty] = ['total' => 0, 'correct' => 0];
                }

                $difficultyStats[$difficulty]['total']++;
                $totalQuestions++;

                // [RULE-BASED SCORING: Poin = 1 jika Sama, = 0 jika Beda]
                // Validasi dilakukan secara komputasi *array* tanpa perlu query sql dalam *looping*
                $answerModel = $question->answers->where('id', $answerId)->first();
                $isCorrect = $answerModel && $answerModel->is_correct;

                if ($isCorrect) {
                    $difficultyStats[$difficulty]['correct']++;
                    $totalCorrect++; // Tambah Poin +1 Parameter
                }
            }

            // ── 2. Simpan skor per difficulty ke student_difficulty_scores ──
            foreach ($difficultyStats as $difficulty => $stats) {
                $percentage = $stats['total'] > 0
                    ? round(($stats['correct'] / $stats['total']) * 100, 2)
                    : 0;

                StudentDifficultyScore::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'lesson_id'  => $lesson->id,
                        'difficulty' => $difficulty,
                    ],
                    [
                        'total_questions'  => $stats['total'],
                        'correct_answers'  => $stats['correct'],
                        'score_percentage' => $percentage,
                    ]
                );
            }

            // ── 3. Simpan skor total ke student_scores ─────────────────────
            $overallScore = $totalQuestions > 0
                ? round(($totalCorrect / $totalQuestions) * 100, 2)
                : 0;

            $grade = match (true) {
                $overallScore > 90 => 'A',
                $overallScore > 70 => 'B',
                $overallScore > 50 => 'C',
                $overallScore > 40 => 'D',
                default             => 'E',
            };

            // Rule Based
            StudentScore::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'lesson_id'  => $lesson->id,
                ],
                [
                    'score'     => $overallScore,
                    'grade'     => $grade,
                    'is_passed' => $overallScore >= 50,
                ]
            );
        });

        return redirect()->route('student.quiz.result', $lesson->uuid)
            ->with('success', 'Quiz berhasil disubmit!');
    }

    /**
     * Tampilkan hasil quiz beserta breakdown per difficulty.
     */
    public function result(Lesson $lesson)
    {
        $student = Auth::user()->student;

        $overallScore = StudentScore::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $difficultyScores = StudentDifficultyScore::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->get()
            ->keyBy('difficulty');

        // Ambil semua materi beserta soal dan opsi jawabannya
        $materials = $lesson->materials()->with(['questions.answers'])->get();
        $questionIds = $materials->flatMap->questions->pluck('id');
        
        // Ambil riwayat seluruh jawaban student di quiz ini secara bulk (hindari N+1 query)
        $studentAnswers = StudentAnswer::where('student_id', $student->id)
            ->whereIn('question_id', $questionIds)
            ->get()
            ->keyBy('question_id');

        $questions = collect();
        $adaptiveFeedbacks = [];

        foreach ($materials as $material) {
            $materialQuestions = $material->questions;
            $materialTotal = $materialQuestions->count();
            $materialCorrect = 0;

            foreach ($materialQuestions as $question) {
                $studentAnswer = $studentAnswers->get($question->id);
                
                $isCorrect = false;
                if ($studentAnswer) {
                    $answerModel = $question->answers->where('id', $studentAnswer->answer_id)->first();
                    if ($answerModel && $answerModel->is_correct) {
                        $isCorrect = true;
                        $materialCorrect++;
                    }
                }

                $questions->push([
                    'question'       => $question,
                    'answers'        => $question->answers,
                    'difficulty'     => $material->difficulty,
                    'student_answer' => $studentAnswer,
                ]);
            }

            // [RULE-BASED] PEMBELAJARAN ADAPTIF
            if ($materialTotal > 0) {
                $pct = round(($materialCorrect / $materialTotal) * 100, 2);
                if ($pct < 50) {
                    // Beri tautan langsung agar siswa bisa klik file pdf/dokumen aslinya
                    $docUrl = asset('storage/' . $material->material_path);
                    $adaptiveFeedbacks[] = "Analisis Adaptif: Anda terindikasi sangat lemah pada pokok ujian (Skor hanya {$pct}%). Kami menyarankan Anda untuk meninjau secara mendalam materi yang diujikan pada referensi berikut: <a href=\"{$docUrl}\" target=\"_blank\" class=\"fw-bold text-decoration-underline text-dark\">Buka Dokumen Materi Terkait <i class=\"bi bi-box-arrow-up-right ms-1\"></i></a>";
                }
            }
        }

        return view('quiz.result', compact('lesson', 'overallScore', 'difficultyScores', 'questions', 'adaptiveFeedbacks'));
    }
}
