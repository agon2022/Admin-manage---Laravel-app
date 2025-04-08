<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run()
    {
        DB::table('bookings')->truncate();

        DB::table('bookings')->insert([
            ['product_id' => 2, 'quantity' => 1, 'status' => 'pending', 'user_id' => 1],
            ['product_id' => 3, 'quantity' => 2, 'status' => 'confirmed', 'user_id' => 2],
        ]);
    }
}
