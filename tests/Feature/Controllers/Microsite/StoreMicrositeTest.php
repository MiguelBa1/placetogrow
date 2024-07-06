<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreMicrositeTest extends TestCase
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
}
