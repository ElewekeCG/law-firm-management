<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cases;
use App\Models\Appointments;
use App\Models\Proceedings;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Notifications;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $client;
    protected $lawyer;
    protected $clerk;
    protected $now;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users with different roles
        $this->client = User::factory()->create(['role' => 'client']);
        $this->lawyer = User::factory()->create(['role' => 'lawyer']);
        $this->clerk = User::factory()->create(['role' => 'clerk']);

        $this->now = Carbon::now();
    }

    public function test_user_can_view_dashboard()
    {
        // Create appointments for client
        $appointments = Appointments::factory()->count(2)->create([
            'clientId' => $this->client->id,
            'startTime' => $this->now->copy()->addDays(2),
            'status' => 'scheduled'
        ]);

        // Create court appearances
        $courtAppearance = Appointments::factory()->create([
            'clientId' => $this->client->id,
            'startTime' => $this->now->copy()->addDays(3),
            'type' => 'court_appearance',
            'status' => 'scheduled'
        ]);

        // Create notifications
        Notifications::factory()->count(3)->create([
            'notifiable_id' => $this->client->id,
            'notifiable_type' => User::class
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertViewIs('Dashboard');

        // Assert the view has all required data
        $response->assertViewHas([
            'newAppointments',
            'upcomingCases',
            'notifications'
        ]);
    }

    public function test_getAppointments_returns_correct_data()
    {
        // Create future appointments
        $futureAppointments = Appointments::factory()->count(2)->create([
            'clientId' => $this->client->id,
            'startTime' => $this->now->copy()->addDays(3),
            'status' => 'scheduled'
        ]);

        // Create past appointment (shouldn't be included)
        $pastAppointment = Appointments::factory()->create([
            'clientId' => $this->client->id,
            'startTime' => $this->now->copy()->subDays(1),
            'status' => 'scheduled'
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('dashboard.upcoming'))
            ->assertStatus(200)
            ->assertViewIs('appointments.upcoming');

        $appointments = $response->viewData('appointments');
        $this->assertEquals(2, $appointments->count());
    }

    public function test_getUpcomingCases_returns_correct_data()
    {
        $case = Cases::factory()->create();
        // Create upcoming court appearances
        $upcomingCases = Appointments::factory()->count(2)->create([
            'clientId' => $this->client->id,
            'caseId' => $case->id,
            'startTime' => $this->now->copy()->addDays(3),
            'type' => 'court_appearance',
            'status' => 'scheduled'
        ]);

        // Create cancelled court appearance (shouldn't be included)
        $cancelledCase = Appointments::factory()->create([
            'clientId' => $this->client->id,
            'startTime' => $this->now->copy()->addDays(2),
            'type' => 'court_appearance',
            'status' => 'cancelled'
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('dashboard.upcomingCases'))
            ->assertStatus(200)
            ->assertViewIs('cases.upcoming');

        $cases = $response->viewData('upcomingCases');
        $this->assertEquals(2, $cases->count());
    }

    public function test_getPendingDocs_returns_correct_data()
    {
        $case = Cases::factory()->create();
        // Create pending documents
        $pendingDocs = Proceedings::factory()->count(3)->create([
            'dueDate' => $this->now->copy()->addDays(7),
            'caseId' => $case->id,
            'docStatus' => 'pending'
        ]);

        // Create completed document (shouldn't be included)
        $completedDoc = Proceedings::factory()->create([
            'dueDate' => $this->now->copy()->addDays(7),
            'docStatus' => 'done'
        ]);

        $response = $this->actingAs($this->lawyer)
            ->get(route('dashboard.pendingDocs'))
            ->assertStatus(200)
            ->assertViewIs('cases.upcomingPro');

        $docs = $response->viewData('pendingDocs');
        $this->assertEquals(3, $docs->count());
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }
}
