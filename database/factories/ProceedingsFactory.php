<?php

namespace Database\Factories;
use App\Models\Cases;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\proceedings>
 */
class ProceedingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'caseId' => Cases::query()->inRandomOrder()->first(),
            'description' => fake()->paragraph(),
            'requiredDoc' => fake()->sentence(1),
            'dueDate' => $this->faker->dateTimeBetween('now', '+1 month'),
            'docStatus'=> $this->faker->randomElement(['pending', 'done'])
        ];
    }
}
