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
use Tests\TestCase;

class UpdateMicrositeTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $this->seed(RoleSeeder::class);
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
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
}
