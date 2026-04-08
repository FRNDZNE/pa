<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Lesson;
use App\Models\Material;
use App\Models\Question;
use Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $data['dosen'] = Lecturer::count();
        $data['lesson'] = Lesson::count();
        $data['quiz'] = Question::count();
        $data['student'] = Student::count();
        return view('dashboard.admin', compact('data'));
    }

    public function lecturer()
    {
        $findId = Auth::user()->lecturer->id;
        $data['lesson'] = Lesson::where('lecturer_id', $findId)->count();
        $data['quiz'] = Question::count();
        return view('dashboard.lecturer', compact('data'));
    }

    public function student()
    {
        $student = Auth::user()->student;

        // Total materi yang tersedia
        $data['lesson'] = Lesson::count();

        // Total kuis yang sudah dikerjakan
        $completedQuizzes = \App\Models\StudentScore::where('student_id', $student->id)->get();
        $data['quiz_completed'] = $completedQuizzes->count();
        
        // Rata-rata Skor Keseluruhan
        $data['avg_score'] = $completedQuizzes->avg('score') ?? 0;

        // Jumlah Lulus / Tidak Lulus
        $data['passed'] = $completedQuizzes->where('is_passed', true)->count();
        $data['failed'] = $completedQuizzes->where('is_passed', false)->count();

        // Statistik Berdasarkan Tingkat Kesulitan
        $difficultyStats = \App\Models\StudentDifficultyScore::where('student_id', $student->id)->get();
        
        $data['difficulty'] = [
            'mudah' => [
                'total' => $difficultyStats->where('difficulty', 'mudah')->sum('total_questions'),
                'correct' => $difficultyStats->where('difficulty', 'mudah')->sum('correct_answers'),
            ],
            'sedang' => [
                'total' => $difficultyStats->where('difficulty', 'sedang')->sum('total_questions'),
                'correct' => $difficultyStats->where('difficulty', 'sedang')->sum('correct_answers'),
            ],
            'sulit' => [
                'total' => $difficultyStats->where('difficulty', 'sulit')->sum('total_questions'),
                'correct' => $difficultyStats->where('difficulty', 'sulit')->sum('correct_answers'),
            ],
        ];

        // Hitung persentase kesulitan (menghindari division by zero)
        foreach (['mudah', 'sedang', 'sulit'] as $level) {
            $total = $data['difficulty'][$level]['total'];
            $correct = $data['difficulty'][$level]['correct'];
            $data['difficulty'][$level]['percentage'] = $total > 0 ? round(($correct / $total) * 100, 1) : 0;
        }

        // Ambil 5 kuis terakhir yang dikerjakan beserta status kelulusan
        $data['recent_quizzes'] = \App\Models\StudentScore::where('student_id', $student->id)
            ->with('lesson')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard.student', compact('data'));
    }
}
