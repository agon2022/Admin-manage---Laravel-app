<?php

use Illuminate\Support\Facades\Route;
use App\Modules\ProductGallery\app\Http\Controllers\ProductGalleryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('productgallery', ProductGalleryController::class)->names('productgallery');
});
