<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['client', 'lawyer', 'clerk']),
        ];
    }

    /**
     * Indicate that the user is a client.
     */
    public function client()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'client',
            ];
        });
    }

    /**
     * Indicate that the user is a lawyer.
     */
    public function lawyer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'lawyer',
            ];
        });
    }

    /**
     * Indicate that the user is a clerk.
     */
    public function clerk()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'clerk',
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
