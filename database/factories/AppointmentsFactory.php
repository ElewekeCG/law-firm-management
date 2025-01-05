<?php

namespace Database\Factories;

use App\Models\Cases;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
        $type = $this->faker->randomElement(['consultation', 'case_meeting', 'court_appearance']);

        // Fetch or create a lawyer
        $lawyer = User::query()->where('role', 'lawyer')->inRandomOrder()->first();

        // Fetch or create a client only if the type requires it
        $client = User::query()->where('role', 'client')->inRandomOrder()->first();

        // Fetch or create a case only if the type requires it
        $case = in_array($type, ['case_meeting', 'court_appearance'])
                ? Cases::query()->inRandomOrder()->first() : null;

        return [
            'lawyerId' => $lawyer->id,
            'clientId' => $client->id, // Null if not required
            'caseId' => $case?->id, // Null if not required
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'startTime' => $this->faker->dateTimeBetween('now', '+1 month'),
            'endTime' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['startTime'], $attributes['startTime']->modify('+2 hours'));
            },
            'type' => $type,
            'status' => $this->faker->randomElement(['scheduled', 'confirmed', 'completed', 'cancelled']),
            'location' => $this->faker->address(),
            'notes' => $this->faker->paragraph(2),
        ];
    }
}
