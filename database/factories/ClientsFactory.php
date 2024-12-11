<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientModel>
 */
class ClientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'address' => fake()->streetAddress(),
            'phoneNumber' => fake()->unique()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'propertyManaged' => fake()->boolean(),
        ];
    }
}
