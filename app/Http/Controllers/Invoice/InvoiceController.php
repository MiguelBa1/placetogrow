<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\StoreInvoiceAction;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\PolicyName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Microsite $microsite): Response
    {
        $this->authorize(PolicyName::VIEW_ANY->value, Invoice::class);

        $invoices = $microsite->invoices()->select(
            'id',
            'reference',
            'document_number',
            'name',
            'amount',
            'expiration_date',
        )->orderBy('created_at', 'desc')->get();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'microsite' => $microsite->only('name', 'slug'),
            'documentTypes' => DocumentType::toSelectArray(),
        ]);
    }

    public function store(CreateInvoiceRequest $request, Microsite $microsite): RedirectResponse
    {
        $this->authorize(PolicyName::CREATE->value, Invoice::class);

        if ($microsite->type !== MicrositeType::INVOICE) {
            return redirect()->back()->withErrors(['error' => __('invoices.invalid_microsite_type')]);
        }

        (new StoreInvoiceAction())->execute($microsite, $request->validated());

        return back();
    }
}
