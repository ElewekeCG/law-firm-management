<?php

namespace Database\Factories;
use App\Models\clients;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\properties>
 */
class PropertiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'clientId' => clients::factory(),
            'address' => fake()->address(),
            'rate' => $this->faker->numberBetween(1000, 30000),
            'percentage' => $this->faker->numberBetween(5, 20),
        ];
    }
}
