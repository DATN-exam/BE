<?php

use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\NotificationController;
use App\Http\Controllers\Site\Student\ClassroomController;
use App\Http\Controllers\Site\Student\ExamController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('verify', [AuthController::class, 'verify'])->withoutMiddleware('api')->name('verify');

    Route::group(['prefix' => 'google', 'as' => 'google.'], function () {
        Route::get('url', [AuthController::class, 'getLoginGoogleUrl'])->name('url');
        Route::post('callback', [AuthController::class, 'loginGoogleCallback'])->name('loginGoogleCallback');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');

    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['middleware' => 'auth:api', 'prefix' => 'classrooms', 'as' => 'classrooms.'], function () {
        Route::get('join/{classroomKey}', [ClassroomController::class, 'join'])->name('join');
        Route::get('/', [ClassroomController::class, 'index'])->name('index');
        Route::get('/{classroom}', [ClassroomController::class, 'show'])->name('show');

        Route::group(['prefix' => '{classroom}/exams', 'as' => 'exams.'], function () {
            Route::get('/', [ExamController::class, 'index'])->name('index');
            Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
            Route::post('/{exam}/start', [ExamController::class, 'start'])->name('start');
            Route::get('/{exam}/get-current', [ExamController::class, 'getCurrent'])->name('getCurrent');
            Route::get('/{exam}/history/{examHistory}', [ExamController::class, 'getExamHistoryDetail'])->name('getCurrentDetail');

            //Exam experiment
            Route::get('/{exam}/get-current-experiment', [ExamController::class, 'getCurrentExperiment'])->name('getCurrentExperiment');
            Route::post('/{exam}/start-experiment', [ExamController::class, 'startExperiment'])->name('startExperiment');

        });
    });
    Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
        Route::post('{examHistory}/change-answer/{examAnwser}', [ExamController::class, 'changeAnswer'])->name('changeAnswer');
        Route::post('{examHistory}/submit', [ExamController::class, 'submit'])->name('submit');
    });
});
