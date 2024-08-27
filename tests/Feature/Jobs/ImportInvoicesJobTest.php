<?php

namespace Tests\Feature\Jobs;

use App\Constants\MicrositeType;
use App\Jobs\ImportInvoicesJob;
use App\Mail\ImportInvoicesResultMail;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportInvoicesJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_invoices_job_imports_valid_invoices()
    {
        Mail::fake();
        Storage::fake('local');

        $user = User::factory()->create();
        Microsite::factory()->create([
            'id' => 1,
            'type' => MicrositeType::INVOICE->value,
        ]);

        $filePath = 'imports/valid_invoices.csv';
        Storage::disk('local')->put($filePath, file_get_contents(base_path('tests/Fixtures/valid_invoices.csv')));

        $job = new ImportInvoicesJob($filePath, $user);
        $job->handle();

        $this->assertDatabaseHas('invoices', [
            'reference' => 'INV-001',
            'name' => 'John',
            'amount' => 1000,
        ]);

        Mail::assertSent(ImportInvoicesResultMail::class);
    }

    public function test_import_invoices_job_handles_invalid_data()
    {
        Mail::fake();
        Storage::fake('local');

        $user = User::factory()->create();
        Microsite::factory()->create([
            'id' => 1,
            'type' => MicrositeType::INVOICE->value,
        ]);

        $filePath = 'imports/invalid_invoices.csv';
        Storage::disk('local')->put($filePath, file_get_contents(base_path('tests/Fixtures/invalid_invoices.csv')));

        $job = new ImportInvoicesJob($filePath, $user);
        $job->handle();

        $this->assertDatabaseCount('invoices', 0);

        Mail::assertSent(ImportInvoicesResultMail::class, function ($mail) {
            return $mail->failures->isNotEmpty();
        });
    }
}
