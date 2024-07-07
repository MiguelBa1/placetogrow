<?php

namespace Database\Seeders;

use App\Constants\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as SpatiePermission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permission::cases() as $permission) {
            SpatiePermission::create(['name' => $permission]);
        }
    }
}
