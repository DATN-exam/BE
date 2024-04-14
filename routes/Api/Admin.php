<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::group(['middleware' => ['auth:api', 'admin'], 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    });
});
