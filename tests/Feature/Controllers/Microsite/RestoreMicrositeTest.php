<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\Role;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class RestoreMicrositeTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_restore_microsite()
    {
        $microsite = Microsite::factory()->create(['deleted_at' => now()]);

        $response = $this->actingAs($this->adminUser)->put(route('microsites.restore', $microsite->slug));

        $response->assertRedirect();
        $this->assertDatabaseHas('microsites', [
            'id' => $microsite->id,
            'deleted_at' => null
        ]);
    }
}
