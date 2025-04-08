<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        View::addNamespace('Category', base_path('app/Modules/Category/resources/views/layouts'));
        View::addNamespace('products', base_path('app/Modules/Products/resources/views/layouts'));
        View::addNamespace('Bookings', base_path('app/Modules/Bookings/resources/views/layouts'));
    }
}
