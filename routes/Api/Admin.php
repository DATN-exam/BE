<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TeacherRegistrationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    });

    Route::group(['prefix' => 'teacher', 'as' => 'teacher.'], function () {
        Route::group(['prefix' => 'registration', 'as' => 'registration.'], function () {
            Route::post('{teacherRegistration}/confirm', [TeacherRegistrationController::class, 'confirm'])
                ->middleware('can:confirm,teacherRegistration')
                ->name('confirm');
            Route::post('{teacherRegistration}/deny', [TeacherRegistrationController::class, 'deny'])
                ->middleware('can:deny,teacherRegistration')
                ->name('deny');
            Route::get('/', [TeacherRegistrationController::class, 'show'])->name('show');
        });
    });
});
