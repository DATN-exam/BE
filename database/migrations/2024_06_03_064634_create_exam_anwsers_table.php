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
        Schema::create('exam_anwsers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_history_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('answer_id')->nullable();
            $table->string('answer_text')->nullable();
            $table->double('score')->default(0);
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('exam_history_id')
                ->references('id')
                ->on('exam_histories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('answer_id')
                ->references('id')
                ->on('answers')
                ->onUpdate('cascade')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_anwsers');
    }
};
