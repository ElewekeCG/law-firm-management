<?php

namespace App\Policies;
use App\Models\Appointments;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    /**
     * Determine if the user can view the appointment
     */
    public function view(User $user, Appointments $appointment)
    {
        // Lawyer can view their own appointments
        // Client can view their own appointments
        return $user->id === $appointment->lawyerId ||
               $user->id === $appointment->clientId;
    }

    /**
     * Determine if the user can create appointments
     */
    public function create(User $user)
    {
        // Anyone authenticated can create an appointment
        return true;
    }

    public function edit(User $user)
    {
        // Anyone authenticated can create an appointment
        return true;
    }

    public function cancel(User $user)
    {
        // Anyone authenticated can cancel an appointment
        return true;
    }

    /**
     * Determine if the user can update the appointment
     */
    public function update(User $user, Appointments $appointment)
    {
        // Only the lawyer or client can update the appointment
        return $user->id === $appointment->lawyerId ||
               $user->id === $appointment->clientId;
    }

    /**
     * Determine if the user can delete the appointment
     */
    public function delete(User $user, Appointments $appointment)
    {
        // Only the lawyer can delete an appointment
        return $user->id === $appointment->lawyerId;
    }
}
