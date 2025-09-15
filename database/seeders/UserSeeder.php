<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role['admin'] = Role::where('name', 'admin')->first();
        $role['lecturer'] = Role::where('name', 'lecturer')->first();
        $role['student'] = Role::where('name', 'student')->first();

        User::create([
            'role_id' => $role['admin']->id,
            'uuid' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345'),
            'remember_token' => Str::random(),
        ]);

        $lecturer = User::create([
            'role_id' => $role['lecturer']->id,
            'uuid' => (string) Str::uuid(),
            'name' => 'Lecturer',
            'email' => 'lecturer@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345'),
            'remember_token' => Str::random(),
        ]);

        Lecturer::create([
            'user_id' => $lecturer->id,
            'lecture_number' => 'L12345',
        ]);
        

        $student = User::create([
            'role_id' => $role['student']->id,
            'uuid' => (string) Str::uuid(),
            'name' => 'Student',
            'email' => 'student@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345'),
            'remember_token' => Str::random(),
        ]);
    }
}
