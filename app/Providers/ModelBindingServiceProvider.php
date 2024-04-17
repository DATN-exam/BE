<?php

namespace App\Providers;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModelBindingServiceProvider extends ServiceProvider
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
        Route::bind('student', function ($value) {
            return User::role(UserRole::STUDENT)->findOrFail($value);
        });

        Route::bind('teacher', function ($value) {
            return User::role(UserRole::TEACHER)->findOrFail($value);
        });
    }
}
