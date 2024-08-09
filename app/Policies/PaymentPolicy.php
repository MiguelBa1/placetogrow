<?php

namespace App\Policies;

use App\Constants\Permission;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ANY_TRANSACTION);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_TRANSACTION);
    }
}
