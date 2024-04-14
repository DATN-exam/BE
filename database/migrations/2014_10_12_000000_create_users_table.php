<?php

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');;
            $table->string('google_id')->nullable();
            $table->string('password');
            $table->string('email')->unique();
            $table->enum('status', UserStatus::getValues())->default(UserStatus::WAIT_VERIFY);
            $table->enum('role', UserRole::getValues())->default(UserRole::STUDENT);
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob')->nullable();
            $table->string('ward_id')->nullable();
            $table->string('address')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
