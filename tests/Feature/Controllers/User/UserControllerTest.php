<?php

namespace Tests\Feature\Controllers\User;

use App\Constants\Role;
use App\Models\User;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_view_users_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('users.index'));

        $response->assertOk();
    }


    public function test_admin_can_update_user_roles()
    {
        $user = User::factory()->create();
        $roles = SpatieRole::all()->random(2)->pluck('name')->toArray();

        $response = $this->actingAs($this->adminUser)->put(route('users.updateRoles', $user), [
            'roles' => $roles,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => SpatieRole::where('name', $roles[0])->first()->id,
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => SpatieRole::where('name', $roles[1])->first()->id,
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);
    }
}
