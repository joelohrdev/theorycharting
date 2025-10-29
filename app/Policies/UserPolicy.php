<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    public function restoreStudent(User $user, User $student): bool
    {
        return $student->isStudent() && $student->teacher_id === $user->id;
    }

    public function deleteStudent(User $user, User $student): bool
    {
        return $student->isStudent() && $student->teacher_id === $user->id;
    }
}
