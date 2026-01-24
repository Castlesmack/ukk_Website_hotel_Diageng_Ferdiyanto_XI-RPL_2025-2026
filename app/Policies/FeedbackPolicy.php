<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    /**
     * Determine if the user can view any feedback.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their own
    }

    /**
     * Determine if the user can view the feedback.
     */
    public function view(User $user, Feedback $feedback): bool
    {
        // Guests can only view their own feedback
        if ($user->role === 'guest') {
            return $feedback->user_id === $user->id;
        }
        // Admin and Receptionist can view all
        return in_array($user->role, ['admin', 'receptionist']);
    }

    /**
     * Determine if the user can create feedback.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create
    }

    /**
     * Determine if the user can update the feedback.
     */
    public function update(User $user, Feedback $feedback): bool
    {
        // Only admin and receptionist can respond to feedback
        return in_array($user->role, ['admin', 'receptionist']);
    }

    /**
     * Determine if the user can delete the feedback.
     */
    public function delete(User $user, Feedback $feedback): bool
    {
        // Only admin can delete
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can restore the feedback.
     */
    public function restore(User $user, Feedback $feedback): bool
    {
        return false;
    }

    /**
     * Determine if the user can permanently delete the feedback.
     */
    public function forceDelete(User $user, Feedback $feedback): bool
    {
        return $user->role === 'admin';
    }
}
