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

        $adminName = config('user.admin.name');
        $adminEmail = config('user.admin.email');
        $adminPassword = config('user.admin.password');

        if (empty($adminName) || empty($adminEmail) || empty($adminPassword)) {
            return;
        }

        $user = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => bcrypt($adminPassword),
        ]);

        $user->assignRole($adminRole);
    }
}
