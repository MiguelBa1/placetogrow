<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            DefaultRolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);

        $count = $this->command->ask('How many microsites do you want to create?', 50);

        $this->callWith(CategoryMicrositeSeeder::class, ['count' => $count]);

    }
}
