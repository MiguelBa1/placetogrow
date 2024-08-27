<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ImportInvoicesResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $successCount;
    public Collection $failures;

    public function __construct(int $successCount, Collection $failures)
    {
        $this->successCount = $successCount;
        $this->failures = $failures;
    }

    public function build(): static
    {
        return $this->subject(__('invoices.import.mail.subject'))
            ->view('emails.invoices.import_result')
            ->with([
                'successCount' => $this->successCount,
                'failures' => $this->failures,
            ]);
    }
}
