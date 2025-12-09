<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin.dashboard');
    }

    public function lecturer()
    {
        return view('lecturer.dashboard');
    }

    public function student()
    {
        return view('student.dashboard');
    }
}
