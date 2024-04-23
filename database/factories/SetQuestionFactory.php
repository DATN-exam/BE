<?php

namespace Database\Factories;

use App\Enums\Question\SetQuestionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SetQuestion>
 */
class SetQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => 2,
            'title' => fake()->title(),
            'status' => SetQuestionStatus::ACTIVE,
            'description' => fake()->text(100),
            'note' => fake()->text(100)
        ];
    }
}
