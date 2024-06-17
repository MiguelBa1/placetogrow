<?php

namespace Tests\Feature;

use App\Constants\Role;
use App\Models\Category;
use App\Models\Microsite;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class MicrositeControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $guestUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
        $this->guestUser = User::factory()->create()->assignRole(Role::GUEST);
    }

    public function test_guest_can_view_microsites()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->guestUser)->get(route('microsites.index'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                ->component('Microsites/Index')
                ->has('microsites', 1)
                ->where('microsites.0.name', $microsite->name)
            );
    }

    public function test_guest_can_view_a_single_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->guestUser)->get(route('microsites.show', $microsite));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                ->component('Microsites/Show')
                ->has('microsite')
                ->where('microsite.name', $microsite->name)
            );
    }

    public function test_admin_can_create_microsite()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->adminUser)->post(route('microsites.store'), [
            'name' => 'Test Microsite',
            'logo' => 'https://example.com/logo.png',
            'category_id' => $category->id,
            'payment_currency' => 'USD',
            'payment_expiration' => 30,
            'type' => 'invoice',
        ]);

        $response->assertFound();
        $this->assertDatabaseHas('microsites', ['name' => 'Test Microsite']);
    }

    public function test_admin_can_update_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->put(route('microsites.update', $microsite), [
            'name' => 'Updated Microsite',
            'logo' => 'https://example.com/new-logo.png',
            'category_id' => $microsite->category_id,
            'payment_currency' => 'COP',
            'payment_expiration' => 60,
            'type' => 'subscription',
        ]);

        $response->assertFound();
        $this->assertDatabaseHas('microsites', ['name' => 'Updated Microsite']);
    }

    public function test_admin_can_delete_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('microsites.destroy', $microsite));

        $response->assertFound();
        $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);
    }

    public function test_guest_cannot_create_microsite()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->guestUser)->post(route('microsites.store'), [
            'name' => 'Test Microsite',
            'logo' => 'https://example.com/logo.png',
            'category_id' => $category->id,
            'payment_currency' => 'USD',
            'payment_expiration' => 30,
            'type' => 'invoice',
        ]);

        $response->assertForbidden();
    }
}
