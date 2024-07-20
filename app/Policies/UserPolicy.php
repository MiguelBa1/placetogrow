<?php

namespace App\Policies;

use App\Constants\Permission;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ANY_USER);
    }

    public function updateRole(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_USER_ROLE);
    }
}
