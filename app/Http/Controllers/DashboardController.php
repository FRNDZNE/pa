<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Lecturer;
use App\Models\Material;
use App\Models\Question;
use Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $data['dosen'] = Lecturer::count();
        $data['kelas'] = Kelas::count();
        $data['materi'] = Material::count();
        $data['quiz'] = Question::count();
        return view('admin.dashboard', compact('data'));
    }

    public function lecturer()
    {
        $findId = Auth::user()->lecturer->id;
        $data['kelas'] = Kelas::count();
        $data['materi'] = Material::count();
        $data['quiz'] = Question::count();
        return view('lecturer.dashboard', compact('data'));
    }

    public function student()
    {
        $data['materi'] = Material::count();
        return view('student.dashboard', compact('data'));
    }
}
