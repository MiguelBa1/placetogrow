<?php

namespace Database\Seeders;

use App\Constants\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Role::getRoles() as $role) {
            SpatieRole::create(['name' => $role]);
        }
    }
}
