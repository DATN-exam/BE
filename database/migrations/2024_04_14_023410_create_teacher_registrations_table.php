<?php

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
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
        Schema::create('teacher_registrations', function (Blueprint $table) {
            $table->increments('id');;
            $table->unsignedInteger('user_id');
            $table->enum('status', TeacherRegistrationStatus::getValues())->default(TeacherRegistrationStatus::WAIT);
            $table->text('description');
            $table->unsignedInteger('employee_cofirm_id')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('employee_cofirm_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_registrations');
    }
};
