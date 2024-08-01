<?php

namespace Tests\Feature\Controllers\Invoice;

use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();

        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_view_invoices_index()
    {
        $microsite = Microsite::factory()->create();

        Invoice::factory()->count(5)->create([
            'microsite_id' => $microsite->id
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('microsites.invoices.index', $microsite));

        $response->assertOk();

        $response->assertInertia(
            fn ($page) => $page
                ->component('Invoices/Index')
                ->has('invoices', 5)
                ->has('microsite')
                ->has('documentTypes')
        );
    }

    public function test_admin_can_store_invoice()
    {
        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::INVOICE->value,
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.invoices.store', $microsite), [
            'reference' => 'INV-001',
            'document_type' => DocumentType::CC->value,
            'document_number' => '1234567890',
            'name' => 'John Doe',
            'last_name' => 'Doe',
            'email' => 'test@mail.com',
            'amount' => 1000,
            'phone' => '1234567890',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'reference' => 'INV-001',
            'document_type' => DocumentType::CC->value,
            'document_number' => '1234567890',
            'name' => 'John Doe',
            'last_name' => 'Doe',
            'email' => 'test@mail.com',
            'amount' => 1000,
            'phone' => '1234567890',
        ]);
    }

    public function test_admin_cannot_store_invoice_in_non_invoice_microsite()
    {
        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::BASIC->value,
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.invoices.store', $microsite), [
            'reference' => 'INV-001',
            'document_type' => DocumentType::CC->value,
            'document_number' => '1234567890',
            'name' => 'John Doe',
            'last_name' => 'Doe',
            'email' => 'test@mail.com',
            'amount' => 1000,
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('error');

        $this->assertDatabaseCount('invoices', 0);
    }
}
