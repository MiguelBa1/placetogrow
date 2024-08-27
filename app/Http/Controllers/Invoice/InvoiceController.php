<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\StoreInvoiceAction;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\PolicyName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Jobs\ImportInvoicesJob;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'name',
            'status',
            'document_number',
            'amount',
            'expiration_date',
        )->orderBy('created_at', 'desc')->get();

        return Inertia::render('Invoices/Index', [
            'invoices' => InvoiceListResource::collection($invoices),
            'microsite' => $microsite->only('name', 'slug'),
            'documentTypes' => DocumentType::toSelectArray(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(CreateInvoiceRequest $request, Microsite $microsite): RedirectResponse
    {
        $this->authorize(PolicyName::CREATE->value, Invoice::class);

        if ($microsite->type !== MicrositeType::INVOICE) {
            return redirect()->back()->withErrors(['error' => __('invoices.invalid_microsite_type')]);
        }

        (new StoreInvoiceAction())->execute($microsite, $request->validated());

        return back();
    }

    public function import(Request $request, Microsite $microsite): RedirectResponse
    {
        if ($microsite->type !== MicrositeType::INVOICE) {
            return redirect()->back()->withErrors(['error' => __('invoices.invalid_microsite_type')]);
        }

        $file = $request->file('invoices');
        $user = $request->user();

        $filePath = $file->store('imports', 'local');

        ImportInvoicesJob::dispatch($filePath, $user);

        return redirect()->back()->with('success', __('invoices.import.success'));
    }
}
