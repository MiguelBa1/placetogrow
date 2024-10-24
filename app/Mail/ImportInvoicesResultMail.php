<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImportInvoicesResultMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $failures;

    public function __construct(array $failures = [])
    {
        $this->failures = $failures;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('invoices.import.mail.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoices.import_result',
        );
    }
}
