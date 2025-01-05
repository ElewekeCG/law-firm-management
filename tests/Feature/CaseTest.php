<?php

namespace Tests\Feature;

use App\Models\Cases;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class CaseTest extends TestCase
{
    use RefreshDatabase;

    private $lawyer;
    private $client;
    private $clerk;
    private $case;

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

        $this->case = Cases::factory()->create();

    }
    public function test_lawyer_can_view_all_cases()
    {
        Cases::factory()->count(5)->create();

        $this->actingAs($this->lawyer)
            ->get(route('cases.allCases'))
            ->assertStatus(200)
            ->assertViewHas('caseList');
    }

    public function test_user_can_add_case_with_valid_data()
    {

        $this->actingAs($this->lawyer)
            ->post(route('cases.add'), [
                'suitNumber' => '123456',
                'clientId' => $this->client->id,
                'lawyerId' => $this->lawyer->id,
                'title' => 'Test Case',
                'type' => 'Civil',
                'status' => 'Open',
                'startDate' => now(),
                'assignedCourt' => 'Court A',
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Case added successfully');
    }

    public function test_case_creation_fails_with_invalid_data()
    {
        $this->actingAs($this->lawyer)
            ->post(route('cases.add'), [
                'suitNumber' => '', // Missing required field
                'title' => 'Test Case',
            ])
            ->assertSessionHasErrors(['suitNumber', 'clientId', 'lawyerId', 'type', 'status', 'startDate', 'assignedCourt']);
    }

    public function test_user_can_update_case()
    {
        $this->actingAs($this->lawyer)
            ->put(route('cases.update', $this->case->id), [
                'status' => 'Closed',
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Case updated successfully');

        $this->assertDatabaseHas('cases', ['id' => $this->case->id, 'status' => 'Closed']);
    }
}
