<?php

namespace Tests\Feature;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
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

    public function test_admin_can_create_microsite()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->adminUser)->post(route('microsites.store'), [
            'name' => 'Test Microsite',
            'logo' => 'https://example.com/logo.png',
            'category_id' => $category->id,
            'payment_currency' => CurrencyType::USD->value,
            'payment_expiration' => 30,
            'type' => MicrositeType::INVOICE->value,
            'slug' => 'test-microsite',
            'responsible_name' => 'John Doe',
            'responsible_document_number' => '1234567890',
            'responsible_document_type' => DocumentType::CC->value,
        ]);

        $response->assertFound();
        $this->assertDatabaseHas('microsites', [
            'slug' => 'test-microsite',
            'name' => 'Test Microsite'
        ]);
    }

    public function test_admin_can_update_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->put(route('microsites.update', $microsite), [
            'name' => 'Updated Microsite',
            'logo' => 'https://example.com/new-logo.png',
            'category_id' => $microsite->category_id,
            'payment_currency' => CurrencyType::COP->value,
            'payment_expiration' => 60,
            'type' => MicrositeType::SUBSCRIPTION->value,
            'slug' => $microsite->slug,
            'responsible_name' => 'Jane Doe',
            'responsible_document_number' => '0987654321',
            'responsible_document_type' => DocumentType::CE->value,
        ]);

        $response->assertFound();
        $this->assertDatabaseHas('microsites', [
            'id' => $microsite->id,
            'name' => 'Updated Microsite',
            'responsible_name' => 'Jane Doe'
        ]);
        $response->assertRedirect(route('microsites.index'));
    }

    public function test_admin_can_delete_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('microsites.destroy', $microsite));

        $response->assertFound();
        $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);
        $response->assertRedirect(route('microsites.index'));
    }

    public function test_guest_cannot_create_microsite()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->guestUser)->post(route('microsites.store'), [
            'name' => 'Test Microsite',
            'logo' => 'https://example.com/logo.png',
            'category_id' => $category->id,
            'payment_currency' => CurrencyType::USD->value,
            'payment_expiration' => 30,
            'type' => MicrositeType::INVOICE->value,
            'slug' => 'test-microsite',
            'responsible_name' => 'John Doe',
            'responsible_document_number' => '1234567890',
            'responsible_document_type' => DocumentType::CC->value,
        ]);

        $response->assertForbidden();
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
