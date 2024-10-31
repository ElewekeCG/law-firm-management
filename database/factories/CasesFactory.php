<?php

namespace Database\Factories;

use App\Models\clients;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\cases>
 */
class CasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->title(),
            'type' => fake()->type(),
            'status' => fake()->status(),
            'clientId' => clients::factory(),
            'suitNumber' => strtoupper($this->faker->unique()->lexify('?????-?????')), // Generates a random suiy number like 'ABCD-EFGHI'
            'startDate' => $this->faker->date(),
            'nextAdjournedDate' => $this->faker->dateTime(),
            'assignedCourt' => fake()->sentence(),
        ];
    }
}
