<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminLecturerServices;

class AdminDosenController extends Controller
{
    public function index()
    {
        return view('admin.datadosen');
    }
}
