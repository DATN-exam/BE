<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('set_question_id');
            $table->unsignedInteger('classroom_id');
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->time('working_time')->default('01:00:00');
            $table->string('note')->nullable();
            $table->unsignedInteger('number_question_hard');
            $table->unsignedInteger('number_question_medium');
            $table->unsignedInteger('number_question_easy');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('set_question_id')
                ->references('id')
                ->on('set_questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('classroom_id')
                ->references('id')
                ->on('classrooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
