<?php

namespace App\Http\Controllers\CustomerInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerInvoice\SendInvoiceLinkRequest;
use App\Mail\CustomerInvoiceLinkMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class CustomerInvoiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('CustomerInvoices/Index');
    }

    public function sendLink(SendInvoiceLinkRequest $request): RedirectResponse
    {
        $email = $request->get('email');
        $documentNumber = $request->get('document_number');

        $url = URL::temporarySignedRoute(
            'invoices.show',
            now()->addMinutes(60),
            [
                'email' => $email,
                'document_number' => $documentNumber,
            ]
        );

        Mail::to($email)->send(new CustomerInvoiceLinkMail($url));

        return redirect()->back();
    }

    public function show(): Response
    {
        return Inertia::render('CustomerInvoices/Show');
    }
}
