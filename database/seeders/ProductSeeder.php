<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            ['category_id' => 1, 'name' => 'Laptop', 'price' => 1000, 'stock' => 10],
            ['category_id' => 1, 'name' => 'Smartphone', 'price' => 500, 'stock' => 20],
            ['category_id' => 2, 'name' => 'T-shirt', 'price' => 20, 'stock' => 50],
        ]);
    }
}
