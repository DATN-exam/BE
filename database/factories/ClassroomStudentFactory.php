<?php

namespace Database\Factories;

use App\Enums\Classroom\ClassroomStudentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassroomStudent>
 */
class ClassroomStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => 1,
            'classroom_id' => 1,
            'status' => ClassroomStudentStatus::ACTIVE,
        ];
    }
}
