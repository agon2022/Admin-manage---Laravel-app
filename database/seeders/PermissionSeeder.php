<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Thêm dữ liệu vào bảng permissions
        \Spatie\Permission\Models\Permission::create(['name' => 'edit articles']);
        \Spatie\Permission\Models\Permission::create(['name' => 'delete articles']);
        \Spatie\Permission\Models\Permission::create(['name' => 'publish articles']);

        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
