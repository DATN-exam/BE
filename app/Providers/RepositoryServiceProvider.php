<?php

namespace App\Providers;

use App\Repositories\Answer\AnswerRepository;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Repositories\Classroom\ClassroomKeyRepository;
use App\Repositories\Classroom\ClassroomKeyRepositoryInterface;
use App\Repositories\Classroom\ClassroomRepository;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Repositories\Classroom\ClassroomStudentRepository;
use App\Repositories\Classroom\ClassroomStudentRepositoryInterface;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Image\ImageRepositoryInterface;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\SetQuestion\SetQuestionRepository;
use App\Repositories\SetQuestion\SetQuestionRepositoryInterface;
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
        $this->app->bind(ClassroomKeyRepositoryInterface::class, ClassroomKeyRepository::class);
        $this->app->bind(ClassroomStudentRepositoryInterface::class, ClassroomStudentRepository::class);
        $this->app->bind(SetQuestionRepositoryInterface::class, SetQuestionRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(AnswerRepositoryInterface::class, AnswerRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
