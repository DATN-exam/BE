<?php

use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\TeacherController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('verify', [AuthController::class, 'verify'])->name('verify')
        ->withoutMiddleware('api');

    Route::group(['prefix' => 'google', 'as' => 'google.'], function () {
        Route::get('url', [AuthController::class, 'getLoginGoogleUrl'])->name('url');
        Route::get('callback', [AuthController::class, 'loginGoogleCallback'])->name('loginGoogleCallback');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
});

Route::group(['middleware' => ['auth:api', 'teacher'], 'prefix' => 'teachers', 'as' => 'teachers.'], function () {
    Route::post('register', [TeacherController::class, 'register'])->name('register')
        ->withoutMiddleware(['teacher']);
});
