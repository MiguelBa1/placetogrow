<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\StoreInvoiceAction;
use App\Constants\MicrositeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Models\Microsite;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public static function index(Microsite $microsite): Response
    {
        $invoices = $microsite->invoices()->select(
            'id',
            'reference',
            'document_number',
            'name',
            'amount',
            'expiration_date',
        )->get();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'micrositeName' => $microsite->name,
        ]);
    }

    public function store(CreateInvoiceRequest $request, Microsite $microsite): RedirectResponse
    {
        if ($microsite->type !== MicrositeType::INVOICE) {
            return redirect()->back()->withErrors(['error' => __('invoices.invalid_microsite_type')]);
        }

        (new StoreInvoiceAction())->execute($microsite, $request->validated());

        return back();
    }
}
