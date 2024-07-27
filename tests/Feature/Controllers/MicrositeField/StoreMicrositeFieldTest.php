<?php

namespace Tests\Feature\Controllers\MicrositeField;

use App\Constants\Role;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class StoreMicrositeFieldTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_store_microsite_field()
    {
        $microsite = Microsite::factory()->create();

        $this->actingAs($this->adminUser)
            ->post(route('microsites.fields.store', $microsite), [
                'name' => 'custom_field',
                'type' => 'text',
                'validation_rules' => 'required|string|max:255',
                'translation_es' => 'Campo personalizado',
                'translation_en' => 'Custom Field',
                'options' => []
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('microsite_fields', [
            'name' => 'custom_field',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255'
        ]);

        $this->assertDatabaseHas('field_translations', [
            'locale' => 'es',
            'label' => 'Campo personalizado'
        ]);

        $this->assertDatabaseHas('field_translations', [
            'locale' => 'en',
            'label' => 'Custom Field'
        ]);
    }

    public function test_store_microsite_field_with_invalid_data()
    {
        $microsite = Microsite::factory()->create();

        $this->actingAs($this->adminUser)
            ->post(route('microsites.fields.store', $microsite), [
                'name' => 'custom_field',
                'type' => 'text',
                'validation_rules' => 'required|string|max:255',
                'translation_es' => 'Campo personalizado',
                'translation_en' => 'Custom Field',
                'options' => 'invalid_options'
            ])
            ->assertSessionHasErrors('options');

        $this->assertDatabaseMissing('microsite_fields', [
            'name' => 'custom_field',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255'
        ]);
    }
}
