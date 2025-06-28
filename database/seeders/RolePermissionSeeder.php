<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

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
            $module = Module::create($moduleData);

            // Create permissions for each module
            $permissions = ['view', 'create', 'edit', 'delete'];
            foreach ($permissions as $permission) {
                Permission::create([
                    'name' =>  $moduleData['slug'] . '_' . $permission,
                    'guard_name' => 'web',
                    'module_id' => $module->id,
                    'description' => ucfirst($permission) . ' ' . $moduleData['name']
                ]);
            }
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $userRole = Role::create(['name' => 'user']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Assign limited permissions to manager
        $managerRole->givePermissionTo([
            'users_view', 'users_create', 'users_edit',
            'roles_view', 'permissions_view'
        ]);

        // Create admin user
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 1
        ]);
        $admin->assignRole('admin');
    }
}
