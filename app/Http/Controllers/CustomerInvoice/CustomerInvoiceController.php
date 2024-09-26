<?php

namespace App\Http\Controllers\CustomerInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerInvoice\SendInvoiceLinkRequest;
use App\Http\Resources\CustomerInvoice\CustomerInvoiceResource;
use App\Mail\CustomerInvoiceLinkMail;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function show(Request $request): Response
    {
        if (! $request->hasValidSignature()) {
            abort(403, __('message.invalid_link'));
        }

        $invoices = Invoice::where('email', $request->get('email'))
            ->where('document_number', $request->get('document_number'))
            ->with(['microsite', 'payment'])
            ->get();

        $invoicesResource = CustomerInvoiceResource::collection($invoices);

        return Inertia::render('CustomerInvoices/Show', [
            'invoices' => $invoicesResource,
            'customer' => [
                'email' => $request->get('email'),
                'document_number' => $request->get('document_number'),
            ]
        ]);
    }
}
