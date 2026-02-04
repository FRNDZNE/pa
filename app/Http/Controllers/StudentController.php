<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\StudentRequest;

class StudentController extends Controller
{

    public function index()
    {
        $students = User::whereHas('student')->get();
        return view("data-mahasiswa.index", compact("students"));
    }

    public function store (StudentRequest $request)
    {
        $data = $request->validated();
        $role = Role::where('name', 'student')->first();
        DB::transaction(function () use ($data, $role) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_id' => $role->id,
                'uuid' => Str::uuid(),
            ]);
            
            $user->student()->create([
                'student_number' => $data['student_number'],
            ]);
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan.');
        
    }

    public function update(StudentRequest $request, User $user)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $user) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            if (!empty($data['password'])) {
                $user->update([
                    'password' => bcrypt($data['password']),
                ]);
            }

            $user->student->update([
                'student_number' => $data['student_number'],
            ]);
        });

        return back()->with('success', 'Mahasiswa berhasil diupdate.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
