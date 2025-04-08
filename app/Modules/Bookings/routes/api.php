<?php

use App\Modules\Bookings\app\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bookings', BookingController::class)->names('bookings');
});
