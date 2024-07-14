<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\Role;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class DestroyMicrositeTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_delete_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('microsites.destroy', $microsite));

        $response->assertRedirect();

        $this->assertSoftDeleted('microsites', ['id' => $microsite->id]);
    }
}
