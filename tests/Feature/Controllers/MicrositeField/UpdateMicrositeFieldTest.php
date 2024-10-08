<?php

namespace Tests\Feature\Controllers\MicrositeField;

use App\Constants\Role;
use App\Models\FieldTranslation;
use App\Models\Microsite;
use App\Models\MicrositeField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class UpdateMicrositeFieldTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_update_microsite_field(): void
    {
        $microsite = Microsite::factory()->create();
        $field = MicrositeField::factory()->create([
            'name' => 'old_field',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255',
            'microsite_id' => $microsite->id,
            'modifiable' => true,
        ]);

        FieldTranslation::factory()->create([
            'field_id' => $field->id,
            'locale' => 'es',
            'label' => 'Campo antiguo'
        ]);

        FieldTranslation::factory()->create([
            'field_id' => $field->id,
            'locale' => 'en',
            'label' => 'Old Field'
        ]);

        $this->actingAs($this->adminUser)
            ->put(route('microsites.fields.update', [$microsite, $field]), [
                'name' => 'updated_field',
                'type' => 'email',
                'validation_rules' => 'required|email|max:100',
                'translation_es' => 'Campo actualizado',
                'translation_en' => 'Updated Field',
                'options' => []
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('microsite_fields', [
            'id' => $field->id,
            'name' => 'updated_field',
            'type' => 'email',
            'validation_rules' => 'required|email|max:100'
        ]);

        $this->assertDatabaseHas('field_translations', [
            'field_id' => $field->id,
            'locale' => 'es',
            'label' => 'Campo actualizado'
        ]);

        $this->assertDatabaseHas('field_translations', [
            'field_id' => $field->id,
            'locale' => 'en',
            'label' => 'Updated Field'
        ]);
    }

    public function test_update_not_modifiable_microsite_field(): void
    {
        $microsite = Microsite::factory()->create();
        $field = MicrositeField::factory()->create([
            'name' => 'old_field',
            'type' => 'text',
            'validation_rules' => 'required|string|max:255',
            'microsite_id' => $microsite->id,
            'modifiable' => false,
        ]);

        $this->actingAs($this->adminUser)
            ->put(route('microsites.fields.update', [$microsite, $field]), [
                'name' => 'updated_field',
                'type' => 'email',
                'validation_rules' => 'required|email|max:100',
                'translation_es' => 'Campo actualizado',
                'translation_en' => 'Updated Field',
                'options' => []
            ])
            ->assertSessionHas('errors');
    }
}
