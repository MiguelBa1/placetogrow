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

    public function test_create_admin_user_command_with_invalid_email()
    {
        Role::create(['name' => 'admin']);

        $this->artisan('create:admin', [
            'name' => 'Admin Name',
            'email' => 'invalid-email',
            'password' => 'securepassword'
        ])->expectsOutput('Validation failed:');

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email',
        ]);
    }

    public function test_create_admin_user_command_with_existing_email()
    {
        Role::create(['name' => 'admin']);

        User::create([
            'name' => 'Existing User',
            'email' => 'admin@example.com',
            'password' => 'securepassword'
        ]);

        $this->artisan('create:admin', [
            'name' => 'Admin Name',
            'email' => 'admin@example.com',
            'password' => 'securepassword'
        ])->expectsOutput('Validation failed:');

        // Ensure no new user is created with the same email
        $users = User::where('email', 'admin@example.com')->get();
        $this->assertCount(1, $users);
    }
}
