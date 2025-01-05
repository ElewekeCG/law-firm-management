<?php

namespace Database\Factories;

use App\Models\User;

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
        $lawyer = User::query()->where('role', 'lawyer')->inRandomOrder()->first();

        // Fetch or create a client only if the type requires it
        $client = User::query()->where('role', 'client')->inRandomOrder()->first();
        return [
            'suitNumber' => strtoupper($this->faker->unique()->lexify('?????-?????')), // Generates a random suit number like 'ABCD-EFGHI'
            'clientId' => $client->id,
            'lawyerId' => $lawyer->id,
            'title' => fake()->sentence(2),
            'type' => $this->faker->randomElement(['criminal', 'civil', 'property']),
            'status' => fake()->sentence(2),
            'startDate' => $this->faker->date(),
            'assignedCourt' => fake()->sentence(),
        ];
    }
}
