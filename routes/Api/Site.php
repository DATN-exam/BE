<?php

use App\Http\Controllers\Site\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('verify', [AuthController::class, 'verify'])->name('verify')
        ->withoutMiddleware('api');
    Route::post('cofirmRegister', [AuthController::class, 'cofirmRegister'])->name('cofirmRegister');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
});
