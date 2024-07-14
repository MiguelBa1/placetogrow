<?php

namespace Tests\Traits;

use Database\Seeders\DefaultRolesAndPermissionsSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;

trait SeedsRolesAndPermissions
{
    use InteractsWithDatabase;

    public function seedRolesAndPermissions(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            DefaultRolesAndPermissionsSeeder::class,
        ]);
    }
}
