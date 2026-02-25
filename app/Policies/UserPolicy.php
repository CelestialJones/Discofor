<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view dashboard.
     */
    public function viewDashboard(User $user): bool
    {
        return !$user->isSuspended();
    }

    /**
     * Determine whether the user can update their profile.
     */
    public function updateProfile(User $user): bool
    {
        return !$user->isSuspended();
    }
}
