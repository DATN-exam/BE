<?php

use App\Http\Controllers\Site\AuthController as AuthControllerSite;
use App\Http\Controllers\Site\TeacherController as TeacherControllerSite;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthControllerSite::class, 'login'])->name('login');
    Route::post('register', [AuthControllerSite::class, 'register'])->name('register');
    Route::post('verify', [AuthControllerSite::class, 'verify'])->name('verify')
        ->withoutMiddleware('api');

    Route::group(['prefix' => 'google', 'as' => 'google.'], function () {
        Route::get('url', [AuthControllerSite::class, 'getLoginGoogleUrl'])->name('url');
        Route::get('callback', [AuthControllerSite::class, 'loginGoogleCallback'])->name('loginGoogleCallback');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('logout', [AuthControllerSite::class, 'logout'])->name('logout');
    Route::get('profile', [AuthControllerSite::class, 'profile'])->name('profile');
});

Route::group(['middleware' => ['auth:api', 'teacher'], 'prefix' => 'teachers', 'as' => 'teachers.'], function () {
    Route::post('register', [TeacherControllerSite::class, 'register'])->name('register')
        ->withoutMiddleware(['teacher']);
});
