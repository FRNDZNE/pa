<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LecturerRequest;
use App\Services\LecturerServices;

class LecturerController extends Controller
{
    public function index()
    {
        $service = new LecturerServices();
        $lecturers = $service->getLecturer();
        return view('admin.data-dosen.index', compact('lecturers'));
    }

    public function store(LecturerRequest $request)
    {
        $service = new LecturerServices();
        $service->storeLecturer($request->all());
        return redirect()->route('admin.lecturer.index')->with('success', 'Lecturer created successfully.');
    }
}
