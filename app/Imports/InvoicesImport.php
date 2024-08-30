<?php

namespace App\Imports;

use App\Constants\InvoiceStatus;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InvoicesImport implements ToModel, WithValidation, WithHeadingRow, ShouldQueue, WithChunkReading
{
    use Importable;


    protected Microsite $microsite;

    public function __construct(Microsite $microsite)
    {
        $this->microsite = $microsite;
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

    public function chunkSize(): int
    {
        return 1000;
    }
}
