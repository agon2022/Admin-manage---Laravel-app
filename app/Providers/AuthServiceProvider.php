<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies(); // Ensure this method is defined below
        Gate::define('manage user', function ($user) {
            // Logic to determine if the user can manage users
            return $user->hasRole('admin'); // Example
        });
        // Đăng ký Permission (cần thiết để Spatie hoạt động đúng)
        Role::find(1); // Kiểm tra có thể truy vấn Role không
    }

    protected function registerPolicies()
    {
        // Define your policy mappings here if needed
    }
}
