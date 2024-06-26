<?php

namespace Database\Factories;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-2 years');
        return [
            'email' => fake()->unique()->safeEmail(),
            'status' => Arr::random([UserStatus::ACTIVE, UserStatus::ADMIN_BLOCK]),
            'role' => UserRole::STUDENT,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'dob' => fake()->dateTimeBetween('-50 years', '-18 years'),
            'address' => fake()->address(),
            'description' => fake()->text(150),
            'password' => static::$password ??= Hash::make('password'),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
