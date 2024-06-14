<?php

namespace Feature\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_admin_user_command()
    {
        Role::create(['name' => 'admin']);

        $this->artisan('create:admin', [
            'name' => 'Admin Name',
            'email' => 'admin@example.com',
            'password' => 'securepassword'
        ])->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Name',
            'email' => 'admin@example.com',
        ]);

        $user = User::where('email', 'admin@example.com')->first();
        $this->assertTrue($user->hasRole('admin'));
    }
}
