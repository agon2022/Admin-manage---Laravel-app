<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Category\app\Http\Controllers\CategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('category', CategoryController::class)->names('category');
});
