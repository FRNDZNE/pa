<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class StudentController extends Controller
{

    public function index()
    {
        return view("data-mahasiswa.index");
    }
}
