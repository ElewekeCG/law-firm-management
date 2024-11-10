<?php

namespace Database\Factories;
use App\Models\cases;

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
            'clientId' => cases::factory(),
            'description' => fake()->sentence(),
            'requiredDoc' => fake()->sentence(),
            'dueDate' => fake()->sentence()
        ];
    }
}
