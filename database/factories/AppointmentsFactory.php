<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\clients;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\appointmentsModel>
 */
class AppointmentsFactory extends Factory
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
            'appointmentDate' => fake()->date(),
            // 'fees' => $this->faker->numberBetween(1000, 30000),
            // 'amountPaid' => $this->faker->numberBetween(5000, 2000000),
            // 'balance' => $this->faker->numberBetween(1000, 30000),
            // 'instructions' => fake()->sentence(),
        ];
    }
}
