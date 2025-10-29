<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class InvitationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->is_admin === true;
    }

    public function createTeacher(User $user): bool
    {
        return $user->is_admin === true;
    }

    public function createStudent(User $user): bool
    {
        return $user->is_admin === true || $user->isTeacher() === true;
    }

    public function resendInvitation(User $user, Invitation $invitation): bool
    {
        return $invitation->teacher_id === $user->id;
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        return $invitation->teacher_id === $user->id;
    }
}
