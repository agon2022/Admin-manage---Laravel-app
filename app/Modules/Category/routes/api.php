<?php

use Illuminate\Support\Facades\Route;
use app\Modules\Category\app\Http\Controllers\CategoryController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('category', CategoryController::class)->names('category');
});
