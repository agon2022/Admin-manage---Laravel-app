<?php // routes/modules/bookings.php

use Illuminate\Support\Facades\Route;
use App\Modules\Products\app\Http\Controllers\ProductController;

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class)->names('products');
});
