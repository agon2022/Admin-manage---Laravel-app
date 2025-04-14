<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Category\app\Http\Controllers\CategoryController;
use app\Modules\Category\app\Models\Category;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('category', CategoryController::class)->names('category');
});
