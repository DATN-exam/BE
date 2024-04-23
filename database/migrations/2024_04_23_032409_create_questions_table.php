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
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('set_question_id');
            $table->string('question');
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedSmallInteger('type')->default(1);
            $table->unsignedFloat('score');
            $table->boolean('is_testing');
            $table->timestamps();

            $table->foreign('set_question_id')
                ->references('id')
                ->on('set_questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
