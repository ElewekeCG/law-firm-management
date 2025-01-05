<?php

namespace Tests\Feature;

use App\Models\Properties;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    private $lawyer;
    private $client;
    private $clerk;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF protection for tests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Disable notifications for tests
        Notification::fake();

        // Create test users

        $this->client = User::factory()->create(['role' => 'client']);

        $this->clerk = User::factory()->create(['role' => 'clerk']);

        $this->lawyer = User::factory()->create(['role' => 'lawyer']);

    }
    public function test_lawyer_can_view_all_properties()
    {
        Properties::factory()->count(5)->create();

        $this->actingAs($this->lawyer)
            ->get(route('properties.view'))
            ->assertStatus(200)
            ->assertViewHas('propertyList');
    }

    public function test_user_can_add_property_with_valid_data()
    {

        $this->actingAs($this->clerk)
            ->post('properties/addProp', [
                'clientId' => $this->client->id,
                'address' => '21 Andover street',
                'rate' => 20000,
                'percentage' => 20,
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Property added successfully');
    }

    public function test_property_creation_fails_with_invalid_data()
    {
        $this->actingAs($this->clerk)
            ->post('properties/addProp', [
                'clientId' => '', // Missing required field
            ])
            ->assertSessionHasErrors(['clientId', 'address', 'rate', 'percentage']);
    }

    public function test_user_can_update_property()
    {
        $prop = Properties::factory()->create();
        $this->actingAs($this->clerk)
            ->put(route('properties.update', $prop->id), [
                'percentage' => 10,
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Property updated successfully');

        $this->assertDatabaseHas('properties', ['id' => $prop->id, 'percentage' => 10]);
    }
}
