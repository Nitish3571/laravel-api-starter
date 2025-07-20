<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Module;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create modules
        $modules = [
            ['name' => 'User Management', 'slug' => 'users'],
            ['name' => 'Role Management', 'slug' => 'roles'],
            ['name' => 'Permission Management', 'slug' => 'permissions'],
            ['name' => 'Module Management', 'slug' => 'modules'],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::firstOrCreate($moduleData);

            // Create permissions for each module
            $permissions = ['view', 'create', 'edit', 'delete'];
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' =>  $moduleData['slug'] . '_' . $permission,
                    'guard_name' => 'web',
                    'module_id' => $module->id,
                    'description' => ucfirst($permission) . ' ' . $moduleData['name']
                ]);
            }
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => UserTypeEnum::SUPER_ADMIN_TYPE_NAME]);
        $adminRole = Role::firstOrCreate(['name' => UserTypeEnum::ADMIN_TYPE_NAME]);
        $userRole = Role::firstOrCreate(['name' => UserTypeEnum::USER_TYPE_NAME]);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());


        // Create admin user
        // $admin = \App\Models\User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('password'),
        //     'status' => 1
        // ]);
        // $admin->assignRole('admin');
    }
}
