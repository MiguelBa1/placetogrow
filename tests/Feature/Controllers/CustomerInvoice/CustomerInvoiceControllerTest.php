<?php

namespace Tests\Feature\Controllers\CustomerInvoice;

use App\Mail\CustomerInvoiceLinkMail;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerInvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_displays_the_customer_invoice_index_page()
    {
        $response = $this->get(route('invoices.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) =>
        $page->component('CustomerInvoices/Index')
        );
    }

    public function test_sends_the_invoice_link_via_email()
    {
        Mail::fake();

        $requestData = [
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ];

        Invoice::factory()->create($requestData);

        $response = $this->post(route('invoices.send-link'), $requestData);

        $response->assertRedirect();

        Mail::assertQueued(CustomerInvoiceLinkMail::class, function ($mail) use ($requestData) {
            return $mail->hasTo($requestData['email']);
        });
    }

    public function test_displays_invoices_with_a_valid_signed_url()
    {
        $microsite = Microsite::factory()->create();
        Invoice::factory()->create([
            'email' => 'test@example.com',
            'document_number' => '1234567890',
            'microsite_id' => $microsite->id,
        ]);

        $url = URL::temporarySignedRoute('invoices.show', now()->addMinutes(60), [
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $response = $this->get($url);

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) =>
        $page->component('CustomerInvoices/Show')
            ->has('invoices')
            ->where('customer.email', 'test@example.com')
            ->where('customer.document_number', '1234567890')
        );
    }

    public function test_aborts_if_the_link_is_invalid_or_expired()
    {
        $url = route('invoices.show', [
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $response = $this->get($url);

        $response->assertForbidden();
        $response->assertSee(__('message.invalid_link'));
    }
}
