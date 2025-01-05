<?php

namespace App\Policies;
use App\Models\properties;
use App\Models\Reports;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view(User $user, properties $property): bool
    {
        return $user->id === $property->clientId
        || in_array($user->role, ['clerk', 'lawyer']);
    }

    public function create(User $user)
    {
        return in_array($user->role, ['clerk', 'lawyer']);
    }
}
