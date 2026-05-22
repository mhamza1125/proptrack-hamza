<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Admins can do everything — bypass all checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Agents can view their own listings; admins handled by before().
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('agent');
    }

    public function view(User $user, Property $property): bool
    {
        return $user->id === $property->user_id;
    }

    /**
     * Only agents can create properties (agents create listings).
     */
    public function create(User $user): bool
    {
        return $user->hasRole('agent');
    }

    /**
     * Agents can only update their own properties.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id;
    }

    /**
     * Agents can only delete their own properties when no active inquiries exist.
     */
    public function delete(User $user, Property $property): bool
    {
        if ($user->id !== $property->user_id) {
            return false;
        }

        return ! $property->hasActiveInquiries();
    }

    public function restore(User $user, Property $property): bool
    {
        return $user->id === $property->user_id;
    }

    public function forceDelete(User $user, Property $property): bool
    {
        return false;
    }
}
