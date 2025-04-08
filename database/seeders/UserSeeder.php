<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;  // Thêm dòng này
use Spatie\Permission\Models\Permission; // Thêm dòng này
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Tạo vai trò nếu chưa có
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Tạo quyền nếu chưa có
        $editPermission = Permission::firstOrCreate(['name' => 'edit articles']);
        $viewPermission = Permission::firstOrCreate(['name' => 'view articles']);

        // Tạo user admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password')
        ]);

        $admin->assignRole($adminRole);
    }
}
