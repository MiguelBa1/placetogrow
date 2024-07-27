<?php

namespace Tests\Feature\Controllers\MicrositeField;

use App\Constants\Role;
use App\Models\Microsite;
use App\Models\MicrositeField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class DestroyMicrositeFieldTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_destroy_microsite_field(): void
    {
        $microsite = Microsite::factory()->create();
        $field = MicrositeField::factory()->create([
            'name' => 'field_to_delete',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255'
        ]);

        $microsite->fields()->attach($field->id, ['modifiable' => true]);

        $this->actingAs($this->adminUser)
            ->delete(route('microsites.fields.destroy', [$microsite, $field]))
            ->assertRedirect();

        $this->assertDatabaseMissing('microsite_fields', [
            'id' => $field->id,
            'name' => 'field_to_delete',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255'
        ]);
    }

    public function test_destroy_not_modifiable_microsite_field(): void
    {
        $microsite = Microsite::factory()->create();
        $field = MicrositeField::factory()->create([
            'name' => 'field_to_delete',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255'
        ]);

        $microsite->fields()->attach($field->id, ['modifiable' => false]);

        $this->actingAs($this->adminUser)
            ->delete(route('microsites.fields.destroy', [$microsite, $field]))
            ->assertRedirect()
            ->assertSessionHas('errors');


        $this->assertDatabaseHas('microsite_fields', [
            'id' => $field->id,
            'name' => 'field_to_delete',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255'
        ]);
    }

}
