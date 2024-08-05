<?php

namespace Tests\Feature\Controllers\MicrositeField;

use App\Constants\FieldType;
use App\Constants\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class MicrositeFieldControllerTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_get_field_types()
    {
        $this->actingAs($this->adminUser)
            ->get(route('microsites.fields.types'))
            ->assertOk()
            ->assertJson(FieldType::toSelectArray());
    }
}
