<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Reports;
use App\Models\Properties;
use App\Models\Transactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Tenants;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
    public function it_shows_generate_report_view()
    {
        $this->actingAs($this->clerk)
        ->get(route('reports.generate'))
        ->assertStatus(200)
        ->assertViewIs('reports.generate')
        ->assertViewHas('props');
    }

    /** @test */
    public function it_shows_property_report()
    {
        $property = Properties::factory()->create();
        Reports::factory()->create(['propertyId' => $property->id]);

        $this->actingAs($this->clerk)
        ->get(route('reports.property', $property))
        ->assertStatus(302)
        ->assertRedirect();
        // ->assertViewHas('reports');
    }

    /** @test */
    public function it_generates_property_report()
    {
        $property = Properties::factory()->create(['percentage' => 10]);
        Transactions::factory()->count(5)->create([
            'amount' => 1000,
            'type' => 'credit',
            // 'subtype' => 'rent',
            'propertyId' => $property->id,
        ]);

        Transactions::factory()->count(2)->create([
            'amount' => 500,
            'type' => 'debit',
            'propertyId' => $property->id,
        ]);

        $this->actingAs($this->clerk)
        ->get(route('reports.property'), [
            'propertyId' => $property->id,
            'startDate' => now()->subMonth()->toDateString(),
            'endDate' => now()->toDateString(),
            'type' => 'property',
        ])
        ->assertStatus(302)
        ->assertRedirect();
    }

    /** @test */
    public function it_generates_firm_report()
    {
        Transactions::factory()->count(5)->create([
            'type' => 'credit',
            'subType' => 'legalFee',
            'amount' => 1000,
        ]);

        Transactions::factory()->count(2)->create([
            'type' => 'debit',
            'propertyId' => null,
            'amount' => 500,
        ]);

        $this->actingAs($this->clerk)
        ->get(route('reports.firm'), [
            'startDate' => now()->subMonth()->toDateString(),
            'endDate' => now()->toDateString(),
        ])
        ->assertStatus(200)
        ->assertViewIs('reports.firm')
        ->assertViewHasAll(['credits', 'expenses', 'totalEarning', 'transactions']);
    }
}
