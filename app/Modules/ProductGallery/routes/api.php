<?php

use Illuminate\Support\Facades\Route;
use App\Modules\ProductGallery\app\Http\Controllers\ProductGalleryController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('productgallery', ProductGalleryController::class)->names('productgallery');
});
