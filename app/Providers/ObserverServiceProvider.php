<?php

namespace App\Providers;

use App\Models\ClassroomKey;
use App\Models\TeacherRegistration;
use App\Models\User;
use App\Observers\ClassroomKeyObserver;
use App\Observers\TeacherRegistrationObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
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
        User::observe(UserObserver::class);
        TeacherRegistration::observe(TeacherRegistrationObserver::class);
        ClassroomKey::observe(ClassroomKeyObserver::class);
    }
}
