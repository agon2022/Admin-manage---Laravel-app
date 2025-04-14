<?php // routes/modules/bookings.php
use Illuminate\Support\Facades\Route;
use App\Modules\Bookings\app\Http\Controllers\BookingController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('bookings', BookingController::class)->names('bookings');
});
