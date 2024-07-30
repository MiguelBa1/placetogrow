<?php

namespace App\Policies;

use App\Constants\Permission;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ANY_INVOICE);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_INVOICE);
    }
}
