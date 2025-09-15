<?php 

namespace App\Services;

use App\Models\Lecturer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdminLecturerServices
{
    public function getData()
    {
        $data = Lecturer::with('user')->get();
        return $data;
    }

    public function storeData($data)
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
            Lecturer::create([
                'user_id' => $user->id,
                'lecture_number' => $data['lecture_number'],
            ]);

            return $user;
        });
    }

    public function updateData($data)
    {
        // Update User tanpa perlu ubah role
        $user = User::find($data['user_id']);

    }

    public function deleteData($data)
    {
    }

    public function exportData()
    {
    }

    public function importData()
    {
    }
    
    
}

?>