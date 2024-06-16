<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = SpatieRole::where('name', Role::ADMIN->value)->first();

        $user = User::create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        $user->assignRole($adminRole);
    }
}
