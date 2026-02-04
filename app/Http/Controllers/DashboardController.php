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
        $data['lesson'] = Lesson::count();
        return view('dashboard.student', compact('data'));
    }
}
