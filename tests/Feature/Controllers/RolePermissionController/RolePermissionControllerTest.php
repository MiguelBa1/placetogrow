<?php

namespace Tests\Feature\Controllers\RolePermissionController;

use App\Constants\Permission;
use App\Constants\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role as SpatieRole;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class RolePermissionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole(Role::ADMIN);
    }

    public function test_admin_can_view_roles_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('roles-permissions.index'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('RolePermissions/Index'));
    }

    public function test_admin_can_store_new_role()
    {
        $roleData = ['name' => $this->faker->word];
        $response = $this->actingAs($this->adminUser)->post(route('roles-permissions.store'), $roleData);
        $response->assertRedirect();
        $this->assertDatabaseHas('roles', ['name' => $roleData['name']]);
    }

    public function test_admin_can_view_role_edit_page()
    {
        $role = SpatieRole::create(['name' => 'tester']);
        $response = $this->actingAs($this->adminUser)->get(route('roles-permissions.edit', $role));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('RolePermissions/Edit')->has('role'));
    }

    public function test_admin_can_update_role_permissions()
    {
        $role = SpatieRole::create(['name' => 'updater']);
        $permissions = [Permission::UPDATE_MICROSITE->value, Permission::DELETE_MICROSITE->value];
        $response = $this->actingAs($this->adminUser)->put(route('roles-permissions.update', $role), [
            'permissions' => $permissions
        ]);

        $response->assertRedirect();

        foreach ($permissions as $permission) {
            $this->assertTrue($role->hasPermissionTo($permission));
        }
    }
}
