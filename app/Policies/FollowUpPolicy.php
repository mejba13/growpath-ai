<?php

namespace App\Policies;

use App\Models\FollowUp;
use App\Models\User;

class FollowUpPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-follow-ups');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FollowUp $followUp): bool
    {
        // Can view if own follow-up
        return $user->id === $followUp->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-follow-ups');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FollowUp $followUp): bool
    {
        // Can update if own follow-up
        return $user->id === $followUp->user_id && $user->can('edit-follow-ups');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FollowUp $followUp): bool
    {
        // Can delete if own follow-up
        return $user->id === $followUp->user_id && $user->can('delete-follow-ups');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FollowUp $followUp): bool
    {
        return $this->delete($user, $followUp);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FollowUp $followUp): bool
    {
        return $user->can('delete-follow-ups');
    }
}
