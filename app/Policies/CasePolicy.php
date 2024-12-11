<?php

namespace App\Policies;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view(User $user, Cases $cases): ?bool //can return boolean or null
    {
        if (in_array($user->role, ['lawyer', 'clerk'])) {
            return true;
        }

        // Clients can only view cases they are involved in
        if ($user->role === 'client') {
            return $cases->client_id === $user->id ?: null;
        }

        return null;
    }

    public function create(User $user)
    {
        // only lawyers and clerks can create cases
        return in_array($user->role, ['lawyer', 'clerk']);
    }

    public function update(User $user, Cases $cases)
    {
        return in_array($user->role, ['lawyer', 'clerk']);
    }
}
