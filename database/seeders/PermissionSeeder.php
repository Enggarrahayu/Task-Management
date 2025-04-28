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
            // Project permissions
            'view_any_project',
            'view_project',
            'create_project',
            'update_project',
            'delete_project',
            'delete_any_project',
            'restore_project',
            'force_delete_project',

            // Task permissions
            'view_any_task',
            'view_task',
            'create_task',
            'update_task',
            'delete_task',
            'delete_any_task',
            'restore_task',
            'force_delete_task',

            // Comment (TaskComment) permissions
            'view_any_task_comment',
            'view_task_comment',
            'create_task_comment',
            'update_task_comment',
            'delete_task_comment',
            'delete_any_task_comment',
            'restore_task_comment',
            'force_delete_task_comment',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);

        // Admin: Full access
        $adminRole->givePermissionTo(Permission::all());

        // Manager: Manage project and tasks (except delete/force actions)
        $managerRole->givePermissionTo([
            'view_any_project',
            'view_project',
            'create_project',
            'update_project',
            'view_any_task',
            'view_task',
            'create_task',
            'update_task',
            'view_any_task_comment',
            'view_task_comment',
            'create_task_comment',
            'update_task_comment',
        ]);

        // Staff: Only view tasks and manage comments
        $staffRole->givePermissionTo([
            'view_any_task',
            'view_task',
            'view_any_task_comment',
            'view_task_comment',
            'create_task_comment',
            'update_task_comment',
        ]);
    }
}
