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

        DB::transaction(function () use ($student, $lesson, $submittedAnswers) {

            // ── 1. Simpan student_answers & hitung skor per difficulty ────
            $difficultyStats = []; // ['mudah' => ['total' => 0, 'correct' => 0], ...]
            $totalCorrect = 0;
            $totalQuestions = 0;

            foreach ($submittedAnswers as $questionId => $answerId) {
                // Simpan jawaban student
                StudentAnswer::create([
                    'question_id' => $questionId,
                    'answer_id'   => $answerId,
                    'student_id'  => $student->id,
                ]);

                // Ambil question beserta material untuk tahu difficulty
                $question = Question::with('material')->find($questionId);
                if (!$question) continue;

                $difficulty = $question->material->difficulty ?? 'mudah';

                // Inisialisasi stats per difficulty
                if (!isset($difficultyStats[$difficulty])) {
                    $difficultyStats[$difficulty] = ['total' => 0, 'correct' => 0];
                }

                $difficultyStats[$difficulty]['total']++;
                $totalQuestions++;

                // Cek apakah jawaban benar
                $isCorrect = $question->answers()
                    ->where('id', $answerId)
                    ->where('is_correct', true)
                    ->exists();

                if ($isCorrect) {
                    $difficultyStats[$difficulty]['correct']++;
                    $totalCorrect++;
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
                $overallScore >= 85 => 'A',
                $overallScore >= 70 => 'B',
                $overallScore >= 55 => 'C',
                $overallScore >= 40 => 'D',
                default             => 'F',
            };

            StudentScore::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'lesson_id'  => $lesson->id,
                ],
                [
                    'score'     => $overallScore,
                    'grade'     => $grade,
                    'is_passed' => $overallScore >= 55,
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

        // Ambil detail jawaban student
        $questions = $lesson->materials()
            ->with(['questions.answers'])
            ->get()
            ->flatMap(function ($material) use ($student) {
                return $material->questions->map(function ($question) use ($material, $student) {
                    $studentAnswer = StudentAnswer::where('student_id', $student->id)
                        ->where('question_id', $question->id)
                        ->first();

                    return [
                        'question'       => $question,
                        'answers'        => $question->answers,
                        'difficulty'     => $material->difficulty,
                        'student_answer' => $studentAnswer,
                    ];
                });
            });

        return view('quiz.result', compact('lesson', 'overallScore', 'difficultyScores', 'questions'));
    }
}
