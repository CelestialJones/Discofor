<?php

namespace App\Policies;

use App\Models\Debate;
use App\Models\User;

class DebatePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->isSuspended();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Debate $debate): bool
    {
        return $user->id === $debate->created_by || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Debate $debate): bool
    {
        return $user->id === $debate->created_by || $user->isAdmin();
    }

    /**
     * Determine whether the user can create messages in this debate.
     */
    public function createMessage(User $user, Debate $debate): bool
    {
        return !$user->isSuspended() && $debate->isActive();
    }
}
