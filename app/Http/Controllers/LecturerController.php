<?php

namespace App\Http\Controllers;

use App\Http\Requests\LecturerRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
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
        $role = Role::where('name', 'lecturer')->first();
        DB::transaction(function () use ($data, $role) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_id' => $role->id,
                'uuid' => Str::uuid(),
            ]);
            
            $user->lecturer()->create([
                'lecture_number' => $data['lecture_number'],
            ]);
        });

        return redirect()->back()->with('success', 'Dosen berhasil ditambahkan.');
        
    }

    public function update(LecturerRequest $request, User $user)
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

            $user->lecturer->update([
                'lecture_number' => $data['lecture_number'],
            ]);
        });

        return back()->with('success', 'Dosen berhasil diupdate.');
    }


    public function destroy (User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Dosen berhasil dihapus.');
    }
}
