<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class StoreMicrositeTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;
    private User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
        $this->customerUser = User::factory()->create()->assignRole(Role::CUSTOMER);
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

    public function test_customer_cannot_create_microsite()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->customerUser)->post(route('microsites.store'), [
            'name' => 'Test Microsite',
            'category_id' => $category->id,
            'logo' => UploadedFile::fake()->image('logo.png'),
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
