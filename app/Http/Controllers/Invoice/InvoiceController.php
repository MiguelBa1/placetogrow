<?php

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\StoreInvoiceAction;
use App\Constants\DocumentType;
use App\Constants\ImportStatus;
use App\Constants\MicrositeType;
use App\Constants\PolicyName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\ImportInvoicesRequest;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Imports\InvoicesImport;
use App\Models\Import;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    /**
     * @throws AuthorizationException
     */
    public function import(ImportInvoicesRequest $request, Microsite $microsite): RedirectResponse
    {
        $this->authorize(PolicyName::IMPORT->value, Invoice::class);

        if ($microsite->type !== MicrositeType::INVOICE) {
            return redirect()->back()->withErrors(['error' => __('invoices.invalid_microsite_type')]);
        }

        $file = $request->file('invoices');
        $user = $request->user();

        $filePath = $file->store('imports', 'local');

        /** @var Import $import */
        $import = Import::query()->create([
            'user_id' => $user->id,
            'filename' => $file->getClientOriginalName(),
            'status' => ImportStatus::PENDING->value,
            'errors' => [],
        ]);

        Excel::queueImport(new InvoicesImport($microsite, $import), $filePath, 'local');

        return redirect()->back()->with('success', __('invoices.import.success'));
    }

    /**
     * @throws AuthorizationException
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $this->authorize(PolicyName::IMPORT->value, Invoice::class);

        $templatePath = Storage::disk('public')->path('templates/invoices.csv');

        return response()->download($templatePath, 'invoices.csv');
    }
}
