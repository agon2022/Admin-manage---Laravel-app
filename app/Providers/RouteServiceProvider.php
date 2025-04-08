<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapModuleRoutes();
    }

    protected function mapWebRoutes()
    {
        $path = base_path('routes/web.php');
        if (file_exists($path)) {
            Route::middleware('web')->group($path);
        }
    }

    protected function mapModuleRoutes()
    {
        $modules = ['Category', 'Products', 'bookings']; // Thêm module khác vào đây

        foreach ($modules as $module) {
            $path = base_path("app/Modules/{$module}/Routes/web.php");
            if (file_exists($path)) {
                Route::middleware('web')->group($path);
            }
        }
    }
}
