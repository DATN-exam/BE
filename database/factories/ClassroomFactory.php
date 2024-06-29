<?php

namespace Database\Factories;

use App\Enums\Classroom\ClassroomStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-2 years');

        return [
            'name' => fake()->firstName(10),
            'teacher_id' => 2,
            'status' => ClassroomStatus::ACTIVE,
            'description' => fake()->text(100),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
