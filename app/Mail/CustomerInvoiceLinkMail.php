<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerInvoiceLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('customer_invoices.email_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.customer.invoice-link',
        );
    }
}