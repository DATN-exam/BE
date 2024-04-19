<?php

use App\Http\Controllers\Site\Teacher\ClassroomController;
use App\Http\Controllers\Site\Teacher\ClassroomKeyController;
use App\Http\Controllers\Site\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => ['auth:api', 'teacher'], 'prefix' => 'teachers', 'as' => 'teachers.'], function () {
Route::post('register', [TeacherController::class, 'register'])->name('register')->withoutMiddleware(['teacher']);

Route::group(['prefix' => 'classrooms', 'as' => 'classrooms.'], function () {
    Route::get('/', [ClassroomController::class, 'index'])->name('index');
    Route::post('/', [ClassroomController::class, 'store'])->name('store');
    Route::patch('/{classroom}', [ClassroomController::class, 'update'])
        ->middleware('can:teacherUpdate,classroom')->name('update')
        ->name('update');

    Route::group(['prefix' => '{classroom}/keys', 'as' => 'keys.'], function () {
        Route::post('/', [ClassroomKeyController::class, 'store'])
            ->name('store');
        Route::get('/', [ClassroomKeyController::class, 'index'])->name('index');
        Route::post('/{classroomKey}/block', [ClassroomKeyController::class, 'block'])
            ->middleware('can:block,classroomKey,classroom')
            ->name('block');
        Route::post('/{classroomKey}/active', [ClassroomKeyController::class, 'active'])
            ->middleware('can:active,classroomKey,classroom')
            ->name('active');
    })->middleware('can:teacherManageClassroom,classroom');
});
// });