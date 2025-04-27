<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'view comments',
            'create comments',
            'delete comments'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $staffRole = Role::where('name', 'Staff')->first();

        // Admin: full access
        $adminRole->givePermissionTo(Permission::all());

        // Manager: manage project, tasks (except delete) and comment
        $managerRole->givePermissionTo([
            'view projects',
            'create projects',
            'edit projects',
            'create tasks',
            'edit tasks',
            'view comments',
            'create comments',
            'delete comments',
        ]);

        // Staff: only view tasks and manage comments
        $staffRole->givePermissionTo([
            'view tasks',
            'view comments',
            'create comments',
            'delete comments',
        ]);
    }
}
