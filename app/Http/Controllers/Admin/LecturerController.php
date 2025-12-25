<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LecturerController extends Controller
{
    public function index()
    {
        return view('admin.data-dosen.index');
    }
}
