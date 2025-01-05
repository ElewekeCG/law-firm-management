<?php

namespace Database\Factories;

use App\Models\Appointments;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notifications;
use App\Models\User;

class NotificationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notifications::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $appt = Appointments::query()->inRandomOrder()->first();
        $user = User::query()->inRandomOrder()->first();
        return [
            'data' => json_encode([
                'apptId' => $appt->id,
                'title' => $appt->title,
                'url' => route('appointments.show', $appt->id),
                'icon' => 'fas fa-calendar-days',
                'message' => "Appointment: {$appt->title} canceled",
            ]),
            'notifiable_id' => User::query()->where('role', 'client')->inRandomOrder()->value('id'), // Assign to a random user
            'notifiable_type' => User::query()->where('role', 'client')->inRandomOrder()->value('id'), // Change based on your notifiable model
            'type' => 'App\Notifications\AppointmentCanceled', // Fully qualified Notification class name
            'read_at' => null, // Default to unread
            'created_at' => now(),
            'updated_at' => now(),
            // 'id' => $this->faker->uuid(),
        ];
    }


    /**
     * Indicate that the notification has been read.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the notification is unread.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => null,
            ];
        });
    }
}
