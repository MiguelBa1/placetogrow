<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    // Provisional empty run method
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);

        $user = User::create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        $user->assignRole($adminRole);
    }
}
