<?php 

namespace App\Services;

use App\Models\Lecturer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdminLecturerServices
{
    public function getLecturer()
    {
        $data = Lecturer::with('user')->get();
        return $data;
    }

    public function storeLecturer($data)
    {
        $role = Role::where('name', 'lecturer')->first();
        DB::transaction(function () use ($data, $role) {
            $user = User::create([
                'role_id' => $role->id,
                'uuid' => Str::uuid(),
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('12345'),
            ]);

            // Create Lecturer
            $lecturer =  Lecturer::create([
                'user_id' => $user->id,
                'lecture_number' => $data['lecture_number'],
            ]);

            return [
                'user' => $user,
                'lecturer' => $lecturer,
            ];
        });
    }

    public function updateLecturer($data, $uuid)
    {
        DB::transaction(function () use ($data, $uuid) {
            // Update User tanpa perlu ubah role
            $user = User::where('uuid', $uuid)->first();
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // Update Lecturer
            $lecturer = Lecturer::where('user_id', $user->id)->first();
            $lecturer->update([
                'lecture_number' => $data['lecture_number'],
            ]);

            return [
                'user' => $user,
                'lecturer' => $lecturer,
            ];
        });
    }

    public function deleteLecturer($uuid)
    {
        DB::transaction(function () use ($uuid) {
            $user = User::where('uuid', $uuid)->first();
            $user->delete();
            return $user;
        });
    }

    public function deleteAllLecturer()
    {
        DB::transaction(function ()  {
            $data = User::whereHas('role', function($query) {
                $query->where('name', 'lecturer');
            })->delete();
            return $data;
        });
    }

    public function exportData()
    {
        
    }

    public function importData()
    {
    }
    
    
}

?>