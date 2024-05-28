<?php

namespace Database\Factories;

use App\Enums\Question\QuestionLevel;
use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'set_question_id' => 1,
            'question' => fake()->text(50) . ' ?',
            'type' => QuestionType::MULTIPLE,
            'level' => Arr::random(QuestionLevel::getValues()),
            'status' => QuestionStatus::ACTIVE,
            'score' => 10,
            'is_testing' => false
        ];
    }
}
