<?php

namespace Database\Seeders;

use App\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->state([
            'email' => 'admin@gmail.com',
            'role' => UserRole::ADMIN
        ])->create();

        User::factory(1)->state([
            'email' => 'teacher@gmail.com',
            'role' => UserRole::TEACHER
        ])->create();

        User::factory(1)->state([
            'email' => 'student@gmail.com',
            'role' => UserRole::STUDENT
        ])->create();

        User::factory(100)->create();
    }
}
