<?php

namespace Database\Factories;
use App\Models\Properties;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\tenants>
 */
class TenantsFactory extends Factory
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
            'email' => fake()->unique()->safeEmail(),
            'paymentType' => $this->faker->randomElement(['yearly', 'monthly']),
            'accomType' => fake()->sentence(2),
            'rentAmt' => $this->faker->numberBetween(100000, 1000000),
            'propertyId' => Properties::query()->inRandomOrder()->first()
        ];
    }
}
