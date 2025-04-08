<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\LanguageMiddleware;
// routes/web.php

// Đảm bảo rằng bạn đã bao gồm routes cho bookings từ module
require base_path('app/Modules/Bookings/routes/web.php');
require base_path('app/Modules/Category/routes/web.php');
require base_path('app/Modules/Products/routes/web.php');
require base_path('app/Modules/ProductGallery/routes/web.php');
Route::get('/', function () {
    return redirect('/admin');
});

// 🔹 Route Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// 🔹 Trang Admin (yêu cầu đăng nhập)
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.home');
    Route::resource('users', UserController::class);
    Route::resource('profile', ProfileController::class, ['as' => 'admin']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
});


// 🔹 Trang Home sau khi đăng nhập
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Auth Routes
Auth::routes();
