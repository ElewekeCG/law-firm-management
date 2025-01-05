<?php

namespace Tests\Feature\Auth;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $userData = User::factory()->client()->make()->toArray();
        $userData['password'] = 'password';
        $userData['password_confirmation'] = 'password';

        $response = $this->post('/register', $userData);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }
}
