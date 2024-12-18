<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Appointments;

class cancelledAppt extends Notification
{
    use Queueable;

    protected $appt;
    // protected $notificationType;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Appointments $appt)
    {
        $this->appt = $appt;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'apptId' => $this->appt->id,
            'title' => $this->appt->title,
            'url' => route('appointments.show', $this->appt->id),
            'icon' => 'fas fa-calendar-days',
            'message' => "Appointment: {$this->appt->title} canceled",

        ];
    }
}
