<?php

namespace Tests\Feature;

use App\Models\cases;
use App\Models\proceedings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsTest extends TestCase
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

        // Create test users

        $this->client = User::factory()->create(['role' => 'client']);

        $this->clerk = User::factory()->create(['role' => 'clerk']);

        $this->lawyer = User::factory()->create(['role' => 'lawyer']);

        $this->case = Cases::factory()->create();

    }

    public function test_lawyer_can_view_all_proceedings()
    {
        Proceedings::factory()->count(5)->create();

        $this->actingAs($this->lawyer)
            ->get(route('cases.viewRecord'))
            ->assertStatus(200)
            ->assertViewHas('recordsList');
    }

    public function test_user_can_add_proceeding()
    {
        $this->actingAs($this->lawyer)
            ->post('cases/saveRecord', [
                'caseId' => $this->case->id,
                'description' => 'Test proceeding',
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Record added successfully');
    }

    public function test_user_cannot_add_case_with_invalid_data ()
    {
        $this->actingAs($this->lawyer)
            ->post('cases/saveRecord', [
                'caseId' => '', // Missing required field
            ])
            ->assertSessionHasErrors('caseId', 'description');
    }

    public function test_user_can_update_proceeding()
    {
        $proceeding = proceedings::factory()->create();

        $this->actingAs($this->lawyer)
            ->put(route('cases.updateRecord', $proceeding->id), [
                'caseId' => $proceeding->caseId,
                'description' => 'Updated description',
                'docStatus' => 'done',
            ])
            ->assertRedirect()
            ->assertSessionHas('message', 'Record updated successfully');

        $this->assertDatabaseHas('proceedings', [
            'id' => $proceeding->id,
            'description' => 'Updated description',
            'docStatus' => 'done',
        ]);
    }
}
