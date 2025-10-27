<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class TeacherPolicy
{
    use HandlesAuthorization;

    public function viewAll(User $user): bool
    {
        return $user->is_admin === true;
    }
}
