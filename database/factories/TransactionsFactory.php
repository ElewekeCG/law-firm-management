<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Properties;
use App\Models\Tenants;
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
        // Determine the transaction type
        $type = $this->faker->randomElement(['credit', 'debit']);
        $subType = $type === 'credit' ? $this->faker->randomElement(['legalFee', 'rent']) : null;

        return [
            'amount' => $this->faker->numberBetween(1000, 50000), // Random amount
            'paymentDate' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Random date
            'type' => $type, // Transaction type
            'subType' => $subType, // SubType is only for credit transactions
            'tenantId' => $subType === 'rent' ? Tenants::query()->inRandomOrder()->first()->id : null, // Only for rent
            'clientId' => $subType === 'legalFee' ? User::query()->where('role', 'client')->inRandomOrder()->first()->id : null, // Random client ID
            'propertyId' => $type === 'credit' && $subType === 'rent' // For credit & rent, propertyId is mandatory
                ? Properties::query()->inRandomOrder()->first()->id ?? null
                : ($type === 'debit' // For debit, propertyId is optional
                    ? (rand(0, 1) ? Properties::query()->inRandomOrder()->first()->id : null) // 50% chance for null
                    : null),
            'narration' => $this->faker->sentence(3), // Random text
        ];
    }
}



