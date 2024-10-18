<?php

namespace App\Policies;

use App\Constants\Permission;
use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ANY_PLAN);
    }

    public function view(User $user, Plan $plan): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_PLAN);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_PLAN);
    }

    public function update(User $user, Plan $plan): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_PLAN);
    }

    public function delete(User $user, Plan $plan): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_PLAN);
    }

    public function restore(User $user, Plan $plan): bool
    {
        return $user->hasPermissionTo(Permission::RESTORE_PLAN);
    }
}
