<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->is_active && $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        return $user->is_active && $user->isAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        // Admin cannot delete their own account
        return $user->is_active && $user->isAdmin() && $user->id !== $model->id;
    }

    public function updateRole(User $user, User $model): bool
    {
        // Admin cannot change their own role to prevent lockout
        return $user->is_active && $user->isAdmin() && $user->id !== $model->id;
    }
}
