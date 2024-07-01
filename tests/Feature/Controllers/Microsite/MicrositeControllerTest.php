<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Models\Category;
use App\Models\Microsite;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $this->seed(RoleSeeder::class);
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

    public function test_admin_can_create_microsite()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->adminUser)->post(route('microsites.store'), [
            'name' => 'Test Microsite',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'category_id' => $category->id,
            'payment_currency' => CurrencyType::USD->value,
            'payment_expiration' => 30,
            'type' => MicrositeType::INVOICE->value,
            'responsible_name' => 'John Doe',
            'responsible_document_number' => '1234567890',
            'responsible_document_type' => DocumentType::CC->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('microsites', [
            'name' => 'Test Microsite',
            'responsible_name' => 'John Doe',
            'responsible_document_number' => '1234567890',
            'responsible_document_type' => DocumentType::CC->value,
            'category_id' => $category->id,
        ]);
    }

    public function test_admin_can_update_microsite()
    {
        $category = Category::factory()->create();
        $microsite = Microsite::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.update', $microsite), [
            'name' => 'Updated Microsite',
            'logo' => UploadedFile::fake()->image('new-logo.png'),
            'category_id' => $microsite->category_id,
            'payment_currency' => CurrencyType::COP->value,
            'payment_expiration' => 30,
            'type' => MicrositeType::SUBSCRIPTION->value,
            'responsible_name' => 'Jane Doe',
            'responsible_document_number' => '0987654321',
            'responsible_document_type' => DocumentType::CE->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('microsites', [
            'id' => $microsite->id,
            'name' => 'Updated Microsite',
            'responsible_name' => 'Jane Doe',
            'responsible_document_number' => '0987654321',
            'responsible_document_type' => DocumentType::CE->value,
        ]);
    }

    public function test_admin_can_delete_microsite()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('microsites.destroy', $microsite));

        $response->assertFound();
        $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);
        $response->assertRedirect();
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

    public function test_admin_cannot_upload_too_big_logo()
    {
        $microsite = Microsite::factory()->create();

        $file = UploadedFile::fake()->create('logo.png', 12000);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.update', $microsite), [
            'name' => 'Updated Microsite',
            'logo' => $file,
            'category_id' => $microsite->category_id,
            'payment_currency' => CurrencyType::COP->value,
            'payment_expiration' => now()->addDays(60),
            'type' => MicrositeType::SUBSCRIPTION->value,
            'responsible_name' => 'Jane Doe',
            'responsible_document_number' => '0987654321',
            'responsible_document_type' => DocumentType::CE->value,
        ]);

        $response->assertSessionHasErrors(['logo']);
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

    public function test_admin_can_view_microsite_details()
    {
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('microsites.show', $microsite));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Microsites/Show')
            ->has('microsite')
            ->has('documentTypes')
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
