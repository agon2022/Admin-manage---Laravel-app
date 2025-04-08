<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID người đặt
            $table->unsignedBigInteger('product_id'); // ID sản phẩm
            $table->integer('quantity')->default(1);
            $table->dateTime('booking_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending'); // Trạng thái
            $table->timestamps();

            // Liên kết khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
