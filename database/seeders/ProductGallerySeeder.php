<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductGallerySeeder extends Seeder
{
    public function run()
    {
        DB::table('product_galleries')->insert([
            ['product_id' => 1, 'image' => 'laptop1.jpg'],
            ['product_id' => 1, 'image' => 'laptop2.jpg'],
            ['product_id' => 2, 'image' => 'smartphone1.jpg'],
        ]);
    }
}
