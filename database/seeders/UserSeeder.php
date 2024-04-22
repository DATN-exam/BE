<?php

namespace Database\Seeders;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->state([
            'email' => 'admin@gmail.com',
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE
        ])->create();

        User::factory(1)->state([
            'email' => 'teacher@gmail.com',
            'role' => UserRole::TEACHER,
            'status' => UserStatus::ACTIVE
        ])->create();

        User::factory(1)->state([
            'email' => 'student@gmail.com',
            'role' => UserRole::STUDENT,
            'status' => UserStatus::ACTIVE
        ])->create();

        User::factory(1000)->state([
            'first_name' => 'Teacher',
            'role' => UserRole::TEACHER
        ])->create();

        User::factory(1000)->create();
    }
}
