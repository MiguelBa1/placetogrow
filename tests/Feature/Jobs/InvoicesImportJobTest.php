<?php

namespace Tests\Feature\Jobs;

use App\Constants\ImportStatus;
use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Imports\InvoicesImport;
use App\Mail\ImportInvoicesResultMail;
use App\Models\Import;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Tests\TestCase;

class InvoicesImportJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_invoices_imports_valid_invoices()
    {
        Mail::fake();
        Storage::fake('local');

        $user = User::factory()->create();
        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::INVOICE->value,
        ]);

        $import = Import::factory()->create([
            'user_id' => $user->id,
            'filename' => 'valid_invoices.csv',
            'status' => ImportStatus::PENDING,
            'errors' => [],
        ]);

        $filePath = 'imports/valid_invoices.csv';
        Storage::disk('local')->put($filePath, file_get_contents(base_path('tests/Fixtures/valid_invoices.csv')));

        Excel::import(new InvoicesImport($microsite, $import), $filePath, 'local');

        $this->assertDatabaseHas('invoices', [
            'reference' => 'INV-001',
            'name' => 'John',
            'amount' => 1000,
            'status' => InvoiceStatus::PENDING,
        ]);

        $import->refresh();
        $this->assertEquals(ImportStatus::READY, $import->status);

        Mail::assertQueued(ImportInvoicesResultMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_import_invoices_handles_invalid_data()
    {
        Mail::fake();
        Storage::fake('local');

        $user = User::factory()->create();
        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::INVOICE->value,
        ]);

        $import = Import::factory()->create([
            'user_id' => $user->id,
            'filename' => 'invalid_invoices.csv',
            'status' => ImportStatus::PENDING,
            'errors' => [],
        ]);

        $filePath = 'imports/invalid_invoices.csv';
        Storage::disk('local')->put($filePath, file_get_contents(base_path('tests/Fixtures/invalid_invoices.csv')));

        try {
            Excel::import(new InvoicesImport($microsite, $import), $filePath, 'local');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $this->assertCount(11, $failures);
        }

        $this->assertDatabaseCount('invoices', 0);

        $import->refresh();
        $this->assertEquals(ImportStatus::FAILED, $import->status);
        $this->assertNotEmpty($import->errors);

        Mail::assertQueued(ImportInvoicesResultMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && !empty($mail->failures);
        });
    }
}
