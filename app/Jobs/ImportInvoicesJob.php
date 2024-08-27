<?php

namespace App\Jobs;

use App\Imports\InvoicesImport;
use App\Mail\ImportInvoicesResultMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected User $user;

    public function __construct($filePath, $user)
    {
        $this->filePath = $filePath;
        $this->user = $user;
    }

    public function handle(): void
    {
        $import = new InvoicesImport;

        try {
            $file = Storage::disk('local')->path($this->filePath);

            Excel::queueImport($import, $file);

            $successCount = $import->getImportedRowsCount();
            Mail::to($this->user->email)->send(new ImportInvoicesResultMail($successCount, collect()));

        } catch (ValidationException $e) {
            $failures = $e->failures();

            $failureDetails = collect();
            foreach ($failures as $failure) {
                $failureDetails[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            Mail::to($this->user->email)->send(new ImportInvoicesResultMail(0, $failureDetails));
        }

        Storage::disk('local')->delete($this->filePath);
    }
}
