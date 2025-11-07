<?php

namespace App\Policies;

use App\Models\Prospect;
use App\Models\User;

class ProspectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-prospects');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prospect $prospect): bool
    {
        // Can view if own prospect OR has permission to view all prospects
        return $user->id === $prospect->user_id
            || $user->can('view-all-prospects');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-prospects');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prospect $prospect): bool
    {
        // Can update if own prospect OR has permission to edit all prospects
        return ($user->id === $prospect->user_id && $user->can('edit-prospects'))
            || $user->can('edit-all-prospects');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prospect $prospect): bool
    {
        // Can delete if own prospect OR has permission to delete all prospects
        return ($user->id === $prospect->user_id && $user->can('delete-prospects'))
            || $user->can('delete-all-prospects');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Prospect $prospect): bool
    {
        return $this->delete($user, $prospect);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Prospect $prospect): bool
    {
        return $user->can('delete-all-prospects');
    }
}
