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
        // Randomly decide whether the transaction is linked to a Tenant or a Client
        $entityType = $this->faker->randomElement([tenants::class, clients::class]);
        $entity = $entityType::factory()->create(); // Creates a Tenant or Client instance
        return [
            'amount' => $this->faker->numberBetween(10, 100000), // Random amount between 100 and 10,000
            'paymentDate' => $this->faker->date(),
            'entity_type' => $entityType, // Class name of the related entity
            'entity_id' => $entity->id, // ID of the related entity
            'type' => $this->faker->randomElement(['credit', 'debit']), // Either credit or debit
            'subtype' => $this->faker->randomElement(['Salary', 'Bonus', 'Refund', null]), // Subtype only for credit
            'propertyId'=>properties::factory(),
            'narration' => $this->faker->sentence(), // Random text for narration
        ];
    }
}
