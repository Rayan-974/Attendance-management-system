<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'mark attendance',
            'submit leave',
            'manage leaves',
            'manage attendance',
            'assign tasks',
            'view reports',
            'manage roles',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create roles and assign created permissions
        $roleStudent = Role::findOrCreate('student');
        $roleStudent->givePermissionTo(['mark attendance', 'submit leave']);

        $roleTeacher = Role::findOrCreate('teacher');
        $roleTeacher->givePermissionTo(['manage attendance', 'assign tasks', 'view reports']);

        $roleHR = Role::findOrCreate('hr');
        $roleHR->givePermissionTo(['manage leaves', 'view reports', 'manage users']);

        $roleAdmin = Role::findOrCreate('admin');
        $roleAdmin->givePermissionTo(Permission::all());

        // Migrate existing users based on their 'role' column (if it exists)
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                // Check if role exists before assigning
                if (Role::where('name', $user->role)->exists()) {
                    $user->assignRole($user->role);
                }
            }
        }
    }
}
