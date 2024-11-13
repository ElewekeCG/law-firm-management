<?php

namespace Database\Factories;

use App\Models\clients;
use App\Models\properties;
use App\Models\tenants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\transactions>
 */
class TransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(10, 100000), // Random amount between 100 and 10,000
            'paymentDate' => $this->faker->date(),
            'type' => $this->faker->randomElement(['credit', 'debit']), // Either credit or debit
            'subtype' => $this->faker->randomElement(['legalFee', 'rent']), // Subtype only for credit
            'tenantId'=>tenants::factory(),
            'clientId'=>clients::factory(),
            'propertyId'=>properties::factory(),
            'narration' => $this->faker->sentence(), // Random text for narration
        ];
    }
}
