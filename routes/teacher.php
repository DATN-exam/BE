<?php

use App\Http\Controllers\Site\Teacher\Classroom\ClassroomController;
use App\Http\Controllers\Site\Teacher\Classroom\ClassroomKeyController;
use App\Http\Controllers\Site\Teacher\Question\SetQuestionController;
use App\Http\Controllers\Site\Teacher\Question\QuestionController;
use App\Http\Controllers\Site\Teacher\StudentController;
use App\Http\Controllers\Site\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

Route::post('register', [TeacherController::class, 'register'])->name('register')->withoutMiddleware(['teacher']);

Route::group(['prefix' => 'classrooms', 'as' => 'classrooms.'], function () {
    Route::get('/', [ClassroomController::class, 'index'])->name('index');
    Route::post('/', [ClassroomController::class, 'store'])->name('store');
    Route::patch('/{classroom}', [ClassroomController::class, 'update'])
        ->middleware('can:teacherUpdate,classroom')->name('update')
        ->name('update');

    Route::group(['middleware' => ['can:teacherManageClassroom,classroom'], 'prefix' => '{classroom}/keys', 'as' => 'keys.'], function () {
        Route::post('/', [ClassroomKeyController::class, 'store'])
            ->name('store');
        Route::get('/', [ClassroomKeyController::class, 'index'])->name('index');
        Route::post('/{classroomKey}/block', [ClassroomKeyController::class, 'block'])
            ->middleware('can:block,classroomKey,classroom')
            ->name('block');
        Route::post('/{classroomKey}/active', [ClassroomKeyController::class, 'active'])
            ->middleware('can:active,classroomKey,classroom')
            ->name('active');
    });

    Route::group(['prefix' => '{classroom}/students', 'as' => 'students.'], function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
    });
});

Route::group(['prefix' => 'set-quetions', 'as' => 'set_quetions.'], function () {
    Route::get('/', [SetQuestionController::class, 'index'])
        ->name('index');
    Route::post('/', [SetQuestionController::class, 'store'])
        ->name('store');
    Route::patch('/{setQuestion}', [SetQuestionController::class, 'update'])
        ->middleware('can:update,setQuestion')
        ->name('update');

    Route::group(['middleware' => ['can:update,setQuestion'], 'prefix' => '{setQuestion}/questions', 'as' => 'questions.'], function () {
        Route::post('/', [QuestionController::class, 'store'])
            ->name('store');
        Route::put('/', [QuestionController::class, 'update'])
            ->name('update');
        Route::get('/', [QuestionController::class, 'index'])
            ->name('index');
        Route::post('/{question}', [QuestionController::class, 'update'])
            ->name('update');
    });
});
