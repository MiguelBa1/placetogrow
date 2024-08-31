<?php

namespace Tests\Feature\Controllers\Invoice;

use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Imports\InvoicesImport;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class InvoiceImportTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();

        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_import_invoices()
    {
        Excel::fake();
        Queue::fake();

        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::INVOICE->value,
        ]);

        Storage::fake('local');
        $file = UploadedFile::fake()->create('invoices.csv', 1024, 'text/csv');

        $response = $this->actingAs($this->adminUser)->post(route('microsites.invoices.import', $microsite), [
            'invoices' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', __('invoices.import.success'));

        Excel::assertQueued(InvoicesImport::class, 'local');
    }

    public function test_admin_cannot_import_invoices_for_invalid_microsite_type()
    {
        Excel::fake();
        Queue::fake();

        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::BASIC->value,
        ]);

        Storage::fake('local');
        $file = UploadedFile::fake()->create('invoices.csv', 1024, 'text/csv');

        $response = $this->actingAs($this->adminUser)->post(route('microsites.invoices.import', $microsite), [
            'invoices' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['error' => __('invoices.invalid_microsite_type')]);

        Queue::assertNothingPushed();
    }

}
