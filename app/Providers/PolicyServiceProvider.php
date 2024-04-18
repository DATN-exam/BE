<?php

namespace App\Providers;

use App\Models\Classroom;
use App\Models\TeacherRegistration;
use App\Models\User;
use App\Policies\ClassroomPolicy;
use App\Policies\TeacherRegistrationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class PolicyServiceProvider extends ServiceProvider
{
    protected $policies = [
        TeacherRegistration::class => TeacherRegistrationPolicy::class,
        User::class => UserPolicy::class,
        Classroom::class => ClassroomPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
