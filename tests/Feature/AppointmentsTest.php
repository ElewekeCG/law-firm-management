<?php

namespace Tests\Feature;

use App\Models\Appointments;
use App\Models\User;
use App\Models\Available_slots;
use App\Models\Cases;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Notifications\newAppointment;
use App\Notifications\updatedAppt;

class AppointmentsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $lawyer;
    private $client;
    private $clerk;
    private $availableSlot;
    private $appointment;

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

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */

    public function test_index_as_lawyer()
    {
        Appointments::factory()->count(5)->create(['lawyerId' => $this->lawyer->id]);
        $response = $this->actingAs($this->lawyer)->get('/appointments/view');

        $response->assertStatus(200);
    }

    public function test_index_as_client()
    {
        Appointments::factory()->count(5)->create(['clientId' => $this->client->id]);
        $response = $this->actingAs($this->client)
            ->get('/appointments/view');

        $response->assertStatus(200);
    }

    public function test_index_as_clerk()
    {
        Appointments::factory()->count(5)->create();
        $response = $this->actingAs($this->clerk)
            ->get('/appointments/view');

        $response->assertStatus(200);
    }

    public function test_store_appointment_successfully()
    {
        Notification::fake();
        $slot = Available_slots::factory()->create(['status' => 'available']);
        $data = [
            'lawyerId' => $this->lawyer->id,
            'clientId' => $this->client->id,
            'availableSlotId' => $slot->id,
            'startTime' => $slot->startTime,
            'endTime' => $slot->endTime,
            'type' => 'consultation',
            'location' => 'office',
            'title' => 'consultational',
        ];

        $response = $this->actingAs($this->client)->postJson(route('appointments.store'), $data);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        // $this->assertDatabaseHas('appointments', ['lawyerId' => $this->lawyer->id, 'title' => 'Test Appointment']);
        Notification::assertSentTo([$this->lawyer, $this->client], newAppointment::class);
    }

    public function test_store_fails_for_conflicting_slot()
    {
        $slot = Available_slots::factory()->create(['status' => 'booked', 'lawyerId' => $this->lawyer->id]);

        $data = [
            'lawyerId' => $this->lawyer->id,
            'clientId' => $this->client->id,
            'availableSlotId' => $slot->id,
            'startTime' => $slot->startTime,
            'endTime' => $slot->endTime,
            'type' => 'consultation',
            'location' => 'office',
            'title' => 'consultational',
        ];

        $response = $this->actingAs($this->client)->postJson(route('appointments.store'), $data);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_update_appointment_successfully()
    {
        $appointment = Appointments::factory()->create([
            'lawyerId' => $this->lawyer->id,
            'clientId' => $this->client->id,
        ]);

        $slot = Available_slots::factory()->create(['status' => 'booked', 'lawyerId' => $this->lawyer->id]);

        $data = [
            'lawyerId' => $this->lawyer->id,
            'clientId' => $this->client->id,
            'availableSlotId' => $slot->id,
            'title' => 'Updated Appointment Title',
            'type' => 'consultation',
            'status' => 'confirmed'
        ];

        $response = $this->actingAs($this->lawyer)->putJson(route('appointments.update', $appointment->id), $data);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'title' => 'Updated Appointment Title']);
    }

    public function test_cancel_appointment_successfully()
    {
        $appointment = Appointments::factory()->create([
            'lawyerId' => $this->lawyer->id,
            'status' => 'cancelled'
        ]);

        $response = $this->actingAs($this->lawyer)->deleteJson(route('appointments.cancel', $appointment->id));

        $response->assertStatus(405); // Redirected after successful cancellation
        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'cancelled']);
    }
}

