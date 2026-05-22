<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    /**
     * Admins bypass all checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Agents can view inquiries only for their own properties.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'agent']);
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return $user->id === $inquiry->property->user_id;
    }

    /**
     * Only admins can update inquiry status (handled by before()).
     */
    public function update(User $user, Inquiry $inquiry): bool
    {
        return false;
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return false;
    }
}
