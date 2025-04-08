<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Danh sách quyền
        $permissions = [
            'manage users',
            'manage roles',
            'manage products',
            'manage orders',
        ];

        // Tạo từng quyền trong danh sách
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Tạo role Admin và gán quyền
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Tạo role User với quyền hạn chế hơn
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions(['manage orders']);
    }
}
