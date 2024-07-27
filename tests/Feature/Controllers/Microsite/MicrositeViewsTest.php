<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\Role;
use App\Models\Microsite;
use App\Models\MicrositeField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class MicrositeViewsTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
        $this->guestUser = User::factory()->create()->assignRole(Role::GUEST);
    }

    public function test_admin_can_view_microsites_index()
    {
        Microsite::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)->get(route('microsites.index'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Index')
                    ->has('microsites.data', 10)
            );
    }

    public function test_admin_can_view_create_page()
    {
        $response = $this->actingAs($this->adminUser)->get(route('microsites.create'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Create')
                    ->has('categories')
            );
    }

    public function test_admin_can_view_edit_page()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('microsites.edit', $microsite));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Edit')
                    ->has('microsite')
                    ->has('categories')
            );
    }

    public function test_admin_can_view_show_page(): void
    {
        $microsite = Microsite::factory()->create();
        $field = MicrositeField::factory()->create();

        $microsite->fields()->attach($field->id, ['modifiable' => true]);

        $response = $this->actingAs($this->adminUser)->get(route('microsites.show', $microsite));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Show')
                    ->has('microsite')
                    ->has('fields')
            );
    }

    public function test_admin_can_view_microsite_details()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('microsites.show', $microsite));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Microsites/Show')
                ->has('microsite')
        );
    }

    public function test_guest_cannot_view_create_page()
    {
        $response = $this->actingAs($this->guestUser)->get(route('microsites.create'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_edit_page()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->guestUser)->get(route('microsites.edit', $microsite));

        $response->assertForbidden();
    }
}
