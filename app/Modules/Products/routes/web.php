<?php // routes/modules/bookings.php

use Illuminate\Support\Facades\Route;
use App\Modules\Products\app\Http\Controllers\ProductController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class)->names('products');
});
Route::delete('/product-images/{id}', [ProductController::class, 'deleteImage']);
