<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            User::NAME => 'Admin User',
            User::EMAIL => 'admin@example.com',
            User::PASSWORD => Hash::make('password'),
            // User::USER_TYPE => 'admin',
            User::STATUS => 1,
        ]);
        $admin->assignRole('admin');

        // Create teacher user
        $teacher = User::create([
            User::NAME => 'Manager User',
            User::EMAIL => 'manager@example.com',
            User::PASSWORD => Hash::make('password'),
            // User::USER_TYPE => 'teacher',
            User::STATUS => 1,
        ]);
        $teacher->assignRole('manager');

        // Create student user
        $student = User::create([
            User::NAME => 'General User',
            User::EMAIL => 'user@example.com',
            User::PASSWORD => Hash::make('password'),
            // User::USER_TYPE => 'student',
            User::STATUS => 1,
        ]);
        $student->assignRole('user');
    }
}
