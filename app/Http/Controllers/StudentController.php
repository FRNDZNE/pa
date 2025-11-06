<?php

namespace App\Http\Controllers;

use App\Services\AdminLecturerServices;
use App\Services\KelasServices;
use App\Services\StudentServices;
use Illuminate\Http\Request;
use Auth;

class StudentController extends Controller
{
    public function __construct(AdminLecturerServices $lecturer, StudentServices $student, KelasServices $kelas)
    {
        $this->lecturer = $lecturer;
        $this->student = $student;
        $this->kelas = $kelas;
    }


    public function index()
    {
        $role = Auth::user()->role->name;
        $data['kelas'] = $this->kelas->getKelas($role);
        $data['lecturers'] = $this->lecturer->getLecture();
        return view("{$role}.data-mahasiswa.index", compact('data'));
    }
}
