<?php

namespace Database\Factories;

use App\Models\Reports;
use App\Models\Properties;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reports::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'propertyId' => Properties::factory(),
            'generated_by' => User::factory(), // Assumes this field stores the user who generated the report
            'type' => $this->faker->randomElement(['summary', 'detailed']), // Adjust types based on your application
            'report_data' => json_encode([
                'credits' => $this->faker->numberBetween(1000, 50000),
                'expenses' => $this->faker->numberBetween(500, 20000),
                'professionalFee' => $this->faker->numberBetween(100, 5000),
                'netIncome' => $this->faker->numberBetween(1000, 30000),
                'transactions' => $this->faker->words(5),
            ]),
            'startDate' => $this->faker->date(),
            'endDate' => $this->faker->date(),
        ];
    }
}
