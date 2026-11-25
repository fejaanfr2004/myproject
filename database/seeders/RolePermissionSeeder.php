<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-admin-dashboard',
            'manage-users',
            'manage-roles',
            'manage-permissions',
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'view-orders',
            'manage-orders',
            'manage-categories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roles = [
            'Super Admin' => $permissions,

            'Product Manager' => [
                'view-admin-dashboard',
                'view-products',
                'create-products',
                'edit-products',
                'delete-products',
                'manage-categories',
            ],

            'Order Manager' => [
                'view-admin-dashboard',
                'view-orders',
                'manage-orders',
            ],

            'Customer' => [
                'view-products',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($rolePermissions);
        }
    }
}
