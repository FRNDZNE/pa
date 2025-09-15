<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return view('student.quiz.index', [
            'message' => 'List of quizzes not taken yet.'
        ]);
    }

    public function index2()
    {
        return view('student.quiz.index2', [
            'message' => 'List of quizzes in progress.'
        ]);
    }

    public function index3()
    {
        return view('student.quiz.index3', [
            'message' => 'List of quizzes already taken.'
        ]);
    }
}
