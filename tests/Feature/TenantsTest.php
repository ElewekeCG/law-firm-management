<?php

namespace Tests\Feature;

use App\Models\Properties;
use App\Models\Tenants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class TenantsTest extends TestCase
{
    use RefreshDatabase;

    private $lawyer;
    private $clerk;
    private $client;

    private $prop;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF protection for tests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Disable notifications for tests
        Notification::fake();

        // Create test users
        $this->clerk = User::factory()->create(['role' => 'clerk']);

        $this->client = User::factory()->create(['role' => 'client']);

        $this->lawyer = User::factory()->create(['role' => 'lawyer']);

        $this->prop = Properties::factory()->create();
    }
    public function test_user_can_view_all_tenants()
    {
        Tenants::factory()->count(5)->create();

        $this->actingAs($this->clerk)
            ->get(route('tenants.view'))
            ->assertStatus(200)
            ->assertViewHas('tenants');
    }

    public function test_user_can_add_tenant_with_valid_data()
    {
        $this->actingAs($this->clerk)
            ->post('tenants/addTenant', [
                'firstName' => 'Nkoli',
                'lastName' => 'Andrew',
                'email' => 'andrew@gmail.com',
                'paymentType' => 'yearly',
                'accomType' => 'two rooms',
                'rentAmt' => 430000,
                'propertyId' => $this->prop->id
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Tenant added successfully');
    }

    public function test_tenant_creation_fails_with_invalid_data()
    {
        $this->actingAs($this->clerk)
            ->post('tenants/addTenant', [
                'email' => '', // Missing required field
            ])
            ->assertSessionHasErrors(['firstName', 'lastName', 'email', 'paymentType', 'accomType', 'rentAmt', 'propertyId']);
    }

    public function test_user_can_update_property()
    {
        $tenant = Tenants::factory()->create();
        $this->actingAs($this->clerk)
            ->put(route('tenants.update', $tenant->id), [
                'rentAmt' => 100000,
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Tenant updated successfully');

        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'rentAmt' => 100000]);
    }
}
