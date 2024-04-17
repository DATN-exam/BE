<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TeacherRegistrationController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    });

    Route::group(['prefix' => 'teachers', 'as' => 'teachers.'], function () {
        Route::group(['prefix' => 'registration', 'as' => 'registration.'], function () {
            Route::post('{teacherRegistration}/confirm', [TeacherRegistrationController::class, 'confirm'])
                ->middleware('can:confirm,teacherRegistration')
                ->name('confirm');
            Route::post('{teacherRegistration}/deny', [TeacherRegistrationController::class, 'deny'])
                ->middleware('can:deny,teacherRegistration')
                ->name('deny');
            Route::get('/', [TeacherRegistrationController::class, 'show'])->name('show');
        });

        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
        Route::patch('/{teacher}', [TeacherController::class, 'update'])->name('update');
        Route::post('/{teacher}/block', [TeacherController::class, 'block'])
            ->middleware('can:adminBlock,teacher')
            ->name('block');
        Route::post('/{teacher}/active', [TeacherController::class, 'active'])
            ->middleware('can:adminActive,teacher')
            ->name('block');
    });

    Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
        Route::patch('/{student}', [StudentController::class, 'update'])->name('update');
        Route::post('/{student}/block', [StudentController::class, 'block'])
            ->middleware('can:adminBlock,student')
            ->name('block');
        Route::post('/{student}/active', [StudentController::class, 'active'])
            ->middleware('can:adminActive,student')
            ->name('block');
    });
});
