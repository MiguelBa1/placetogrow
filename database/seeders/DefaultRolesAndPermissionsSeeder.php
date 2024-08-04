<?php

namespace Database\Seeders;

use App\Constants\Permission;
use App\Constants\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;

class DefaultRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            Role::ADMIN->value => [
                Permission::VIEW_ANY_MICROSITE,
                Permission::VIEW_MICROSITE,
                Permission::CREATE_MICROSITE,
                Permission::UPDATE_MICROSITE,
                Permission::DELETE_MICROSITE,
                Permission::RESTORE_MICROSITE,

                Permission::VIEW_ANY_USER,
                Permission::UPDATE_USER_ROLE,

                Permission::VIEW_DASHBOARD,

                Permission::VIEW_ANY_INVOICE,
                Permission::CREATE_INVOICE,

                Permission::VIEW_ANY_TRANSACTION,
            ],
            Role::CUSTOMER->value => [],
            Role::GUEST->value => [],
            Role::ROLE_MANAGER->value => [
                Permission::MANAGE_ROLES,
            ],
        ];

        foreach ($roles as $role => $permissions) {
            $role = SpatieRole::findByName($role);
            $role->givePermissionTo($permissions);
        }
    }
}
