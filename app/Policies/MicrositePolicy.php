<?php

namespace App\Policies;

use App\Constants\Permission;
use App\Models\User;

class MicrositePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ANY_MICROSITE);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_MICROSITE);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_MICROSITE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_MICROSITE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_MICROSITE);
    }

    public function restore(User $user): bool
    {
        return $user->hasPermissionTo(Permission::RESTORE_MICROSITE);
    }

}
