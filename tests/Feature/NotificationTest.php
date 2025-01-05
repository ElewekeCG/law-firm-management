<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Notifications;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_their_notifications()
    {
        // Create some notifications for the user
        $notifications = Notifications::factory()->count(3)->create([
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('notifications.view'))
            ->assertStatus(200)
            ->assertViewIs('notifications.index')
            ->assertViewHas('notifications');

        // Assert pagination is working
        $this->assertEquals(5, $response->viewData('notifications')->perPage());
    }

    public function test_user_can_mark_notification_as_read()
    {
        // Create an unread notification with a URL
        $notification = Notifications::factory()->create([
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['url' => '/dashboard'],
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('notifications.mark-as-read', $notification->id));

        // Assert the notification was marked as read
        $this->assertNotNull($notification->fresh()->read_at);

        // Assert redirect to the URL in notification data
        $response->assertRedirect('/dashboard');
    }

    public function test_user_can_get_unread_notifications_count()
    {
        // Create some unread notifications
        Notifications::factory()->count(2)->create([
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'read_at' => null
        ]);

        // Create a read notification (shouldn't be counted)
        Notifications::factory()->create([
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'read_at' => now()
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('notifications.count'));

        $response->assertStatus(200)
            ->assertJson(['count' => 2]);
    }

    public function test_user_cannot_mark_other_users_notification_as_read()
    {
        // Create another user and their notification
        $otherUser = User::factory()->create();
        $otherNotification = Notifications::factory()->create([
            'notifiable_type' => get_class($otherUser),
            'notifiable_id' => $otherUser->id,
            'read_at' => null
        ]);

        // Try to mark other user's notification as read
        $response = $this->actingAs($this->user)
            ->post(route('notifications.mark-as-read', $otherNotification->id))
            ->assertStatus(404); // Should return 404 as findOrFail() won't find the notification
    }
}
