<?php

namespace App\Imports;

use App\Constants\ImportStatus;
use App\Constants\InvoiceStatus;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Mail\ImportInvoicesResultMail;
use App\Models\Import;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Validators\ValidationException;

class InvoicesImport implements
    ToModel,
    WithBatchInserts,
    WithHeadingRow,
    WithUpserts,
    ShouldQueue,
    WithChunkReading,
    WithValidation,
    WithEvents,
    SkipsEmptyRows
{
    use Importable;

    protected Microsite $microsite;
    private Import $import;

    public function __construct(Microsite $microsite, Import $import)
    {
        $this->microsite = $microsite;
        $this->import = $import;
    }

    public function model(array $row): Invoice
    {
        return new Invoice([
            'microsite_id' => $this->microsite->id,
            'reference' => $row['reference'],
            'document_type' => $row['document_type'],
            'document_number' => $row['document_number'],
            'name' => $row['name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'status' => $row['status'] ?? InvoiceStatus::PENDING,
            'phone' => $row['phone'],
            'amount' => $row['amount'],
            'expiration_date' => $row['expiration_date'],
        ]);
    }

    public function rules(): array
    {
        return (new CreateInvoiceRequest())->rules();
    }

    public function customValidationMessages(): array
    {
        return (new CreateInvoiceRequest())->messages();
    }

    public function customValidationAttributes(): array
    {
        return (new CreateInvoiceRequest())->attributes();
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $exception = $event->getException();

                Log::error('Import failed', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'trace' => $exception->getTraceAsString(),
                ]);

                if ($exception instanceof ValidationException) {
                    $this->import->errors = $exception->failures();
                } else {
                    $this->import->errors = [
                        [
                            'message' => $exception->getMessage(),
                            'file' => $exception->getFile(),
                            'trace' => $exception->getTraceAsString(),
                        ],
                    ];
                }

                $this->import->status = ImportStatus::FAILED;
                $this->import->save();

                Mail::to($this->import->user->email)->send(new ImportInvoicesResultMail($this->import->errors));
                Storage::disk('local')->delete($this->import->filename);
            },
            AfterImport::class => function () {
                $this->import->status = ImportStatus::READY;
                $this->import->save();

                Mail::to($this->import->user->email)->send(new ImportInvoicesResultMail());
                Storage::disk('local')->delete($this->import->filename);
            },
        ];
    }

    public function uniqueBy(): string
    {
        return 'reference';
    }
}
