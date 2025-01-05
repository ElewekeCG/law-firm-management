<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Available_slots>
 */
class Available_slotsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lawyer = User::query()->where('role', 'lawyer')->inRandomOrder()->first();
        return [
            'lawyerId' => $lawyer,
            'startTime' => $this->faker->dateTimeBetween('now', '+1 month'),
            'endTime' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['startTime'], $attributes['startTime']->modify('+2 hours'));
            },
            'status' => $this->faker->randomElement(['available', 'booked']),
        ];
    }
}
