<?php

namespace App\Providers;

use App\Repositories\Classroom\ClassroomRepository;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Repositories\TeacherRegistration\TeacherRegistrationRepository;
use App\Repositories\TeacherRegistration\TeacherRegistrationRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TeacherRegistrationRepositoryInterface::class, TeacherRegistrationRepository::class);
        $this->app->bind(ClassroomRepositoryInterface::class, ClassroomRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
