<?php

use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\Teacher\ClassroomController;
use App\Http\Controllers\Site\Teacher\ClassroomKeyController;
use App\Http\Controllers\Site\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('verify', [AuthController::class, 'verify'])->withoutMiddleware('api')->name('verify');

    Route::group(['prefix' => 'google', 'as' => 'google.'], function () {
        Route::get('url', [AuthController::class, 'getLoginGoogleUrl'])->name('url');
        Route::post('callback', [AuthController::class, 'loginGoogleCallback'])->name('loginGoogleCallback');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
});

Route::group(['middleware' => ['auth:api', 'teacher'], 'prefix' => 'teachers', 'as' => 'teachers.'], function () {
    Route::post('register', [TeacherController::class, 'register'])->name('register')->withoutMiddleware(['teacher']);

    Route::group(['prefix' => 'classrooms', 'as' => 'classrooms.'], function () {
        Route::get('/', [ClassroomController::class, 'index'])->name('index');
        Route::post('/', [ClassroomController::class, 'store'])->name('store');
        Route::patch('/{classroom}', [ClassroomController::class, 'update'])
            ->middleware('can:teacherUpdate,classroom')->name('update');

        Route::group(['prefix' => '{classroom}/keys', 'as' => 'keys.'], function () {
            Route::post('/', [ClassroomKeyController::class, 'store'])
                ->name('store');
            Route::get('/', [ClassroomKeyController::class, 'index'])->name('index');
            Route::post('/{key}', [ClassroomKeyController::class, 'delete'])->name('delete');
        })->middleware('can:teacherManageClassroom,classroom');
    });
});
