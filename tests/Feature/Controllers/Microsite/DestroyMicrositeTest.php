<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\Role;
use App\Models\Microsite;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyMicrositeTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_delete_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('microsites.destroy', $microsite));

        $response->assertFound();
        $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);
        $response->assertRedirect();
    }
}
