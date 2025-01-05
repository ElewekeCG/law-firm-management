<?php

namespace Tests\Feature;

use App\Models\Properties;
use App\Models\Tenants;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    private $tenant;
    private $client;
    private $clerk;

    private $property;

    protected function setUp(): void
    {
      parent::setUp();

      $this->clerk = User::factory()->create(['role' => 'clerk']);
      $this->client = User::factory()->create(['role' => 'client']);
      $this->property = Properties::factory()->count(10)->create();
      $this->tenant = Tenants::factory()->create();
    }
    /** @test */
    public function it_displays_the_transactions_list_with_pagination()
    {
        $this->actingAs($this->clerk);

        // Seed some transactions
        Transactions::factory()->count(15)->create();

        $response = $this->get(route('transactions.view', ['dataTable_length' => 10]));

        $response->assertStatus(200);
        $response->assertViewIs('transactions.index');
        $response->assertViewHas('transList');
    }

    /** @test */
    public function it_displays_the_add_transaction_form()
    {
        $this->actingAs($this->clerk);

        Properties::factory()->count(5)->create();
        User::factory()->count(5)->create(['role' => 'client']);
        Tenants::factory()->count(5)->create();

        $response = $this->get(route('transactions.add'));

        $response->assertStatus(200);
        $response->assertViewIs('transactions.add');
        $response->assertViewHasAll(['properties', 'clients', 'tenants']);
    }

    /** @test */
    public function it_displays_the_edit_transaction_form()
    {
        $this->actingAs($this->clerk);

        $transaction = Transactions::factory()->create();

        $response = $this->get(route('transactions.edit', $transaction->id));

        $response->assertStatus(200);
        $response->assertViewIs('transactions.edit');
        $response->assertViewHasAll(['properties', 'clients', 'tenants', 'trans']);
    }

    /** @test */
    public function it_adds_a_new_transaction()
    {
        $this->actingAs($this->clerk);
        $property = Properties::factory()->create();

        $data = [
            'amount' => 5000,
            'paymentDate' => now()->toDateString(),
            'type' => 'credit',
            'subType' => 'rent',
            'tenantId' => $this->tenant->id,
            'propertyId' => $property->id,
            'narration' => 'Monthly rent payment',
        ];

        $response = $this->post('transactions/create', $data);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Transaction added successfully');

        $this->assertDatabaseHas('transactions', $data);
    }

    /** @test */
    public function it_updates_an_existing_transaction()
    {

        $transaction = Transactions::factory()->create();
        // $property = Properties::factory()->create();

        $this->actingAs($this->clerk)
        ->put(route('transactions.update', $transaction->id), ['amount' => 40000])
        ->assertRedirect()
        ->assertSessionHas('message', 'Transaction updated successfully');

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'amount' => 40000]);
    }

    /** @test */
    public function it_validates_transaction_creation()
    {
        $this->actingAs($this->clerk);

        $response = $this->post('transactions/create', []);

        $response->assertSessionHasErrors([
            'amount',
            'paymentDate',
            'type',
            'narration',
        ]);
    }
}
