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
            'name' => config('user.admin.name'),
            'email' => config('user.admin.email'),
            'password' => bcrypt(config('user.admin.password')),
        ]);

        $user->assignRole($adminRole);
    }
}
