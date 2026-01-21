<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\LecturerRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = User::whereHas('lecturer')->get();
        return view("data-dosen.index", compact('lecturers'));
    }

    public function store (LecturerRequest $request)
    {
        $data = $request->validated();
    }

    public function update (LecturerRequest $request, $uuid)
    {
        
    }

    public function destroy ($uuid)
    {
        
    }
}
