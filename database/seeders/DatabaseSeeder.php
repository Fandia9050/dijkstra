<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat User Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('Admin123@'),
            ]
        );

        // Buat Permissions
        $permissions = [
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat Role
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Hubungkan Role dengan semua Permissions
        foreach (Permission::all() as $permission) {
            RolePermission::firstOrCreate([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ]);
        }

        // Hubungkan User dengan Role Admin
        UserRole::firstOrCreate([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }
}
