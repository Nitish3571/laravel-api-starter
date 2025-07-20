<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::firstOrCreate([
            User::NAME => 'Super Admin',
            User::EMAIL => 'superadmin@gmail.com',
            User::PASSWORD => Hash::make('123567890'),
            User::USER_TYPE => UserTypeEnum::SUPER_ADMIN,
            User::STATUS => 1,
        ]);
        $admin->assignRole(UserTypeEnum::SUPER_ADMIN_TYPE_NAME);

        // Create teacher user
        $teacher = User::firstOrCreate([
            User::NAME => 'Admin',
            User::EMAIL => 'admin@gmail.com',
            User::PASSWORD => Hash::make('1234567890'),
            User::USER_TYPE => UserTypeEnum::ADMIN,
            User::STATUS => 1,
        ]);
        $teacher->assignRole(UserTypeEnum::ADMIN_TYPE_NAME);

        // Create student user
        $student = User::firstOrCreate([
            User::NAME => 'User',
            User::EMAIL => 'user@gmail.com',
            User::PASSWORD => Hash::make('123567890'),
            User::USER_TYPE => UserTypeEnum::USER,
            User::STATUS => 1,
        ]);
        $student->assignRole(UserTypeEnum::USER_TYPE_NAME);
    }
}
