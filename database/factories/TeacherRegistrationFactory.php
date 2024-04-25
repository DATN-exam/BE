<?php

namespace Database\Factories;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeacherRegistration>
 */
class TeacherRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 3,
            'status' => Arr::random([
                TeacherRegistrationStatus::WAIT,
                TeacherRegistrationStatus::CANCEL,
                TeacherRegistrationStatus::DENY
            ]),
            'description' => fake()->text(100),
            'employee_cofirm_id' => null,
            'reason' => null,
        ];
    }
}
