<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Danh sách quyền
        $permissions = ['view users', 'edit users', 'delete users', 'create users'];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Tạo vai trò Admin & User nếu chưa tồn tại
        if (!Role::where('name', 'Admin')->exists()) {
            $admin = Role::create(['name' => 'Admin']);
            $admin->givePermissionTo($permissions);
        }

        if (!Role::where('name', 'User')->exists()) {
            Role::create(['name' => 'User']);
        }
    }
}
