<?php

namespace App\Http\Controllers\InvoicePayment;

use App\Actions\Payment\PrepareInvoicePaymentAction;
use App\Constants\InvoiceStatus;
use App\Contracts\PaymentServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\InvoicePayment\PendingInvoiceListResource;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class InvoicePaymentController extends Controller
{
    private PaymentServiceInterface $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Microsite $microsite): Response
    {
        $micrositeData = [
            'id' => $microsite->id,
            'name' => $microsite->name,
            'logo' => $microsite->getFirstMediaUrl('logos'),
            'slug' => $microsite->slug,
            'type' => $microsite->type,
            'payment_currency' => $microsite->payment_currency,
        ];

        $fields = MicrositeFieldDetailResource::collection(
            $microsite->fields()->with('translations')->get()
        );

        return Inertia::render('Payments/Show', [
            'microsite' => $micrositeData,
            'fields' => $fields,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(CreatePaymentRequest $request, Microsite $microsite, Invoice $invoice): \Symfony\Component\HttpFoundation\Response
    {
        $paymentData = (new PrepareInvoicePaymentAction())->execute($microsite, $invoice, $request->validated());

        $result = $this->paymentService->createPayment($microsite, $paymentData);

        if (!$result['success']) {
            return back()->withErrors([
                'payment' => __('payment.create_failed'),
            ]);
        }

        return Inertia::location($result['url']);
    }

    public function return(Payment $payment): Response|RedirectResponse
    {
        $isSuccessful = $this->paymentService->checkPayment($payment);

        if (!$isSuccessful) {
            return redirect()->route('invoice-payments.show', $payment->microsite->slug)
                ->withErrors([
                    'payment' => __('payment.check_failed'),
                ]);
        }

        return Inertia::render('Payments/Return', [
            'payment' => $payment,
            'customer' => $payment->customer->only(['name', 'last_name']),
            'micrositeName' => $payment->microsite->name,
        ]);
    }

    public function getPendingInvoices(CreatePaymentRequest $request, Microsite $microsite): JsonResource
    {
        $validated = $request->validated();

        $invoices = Invoice::select(
            'id',
            'reference',
            'name',
            'last_name',
            'amount',
            'expiration_date',
            'status',
            'microsite_id'
        )->where('document_number', $validated['document_number'])
            ->where('reference', $validated['reference'])
            ->where('microsite_id', $microsite->id)
            ->where('status', InvoiceStatus::PENDING)
            ->get();

        return PendingInvoiceListResource::collection($invoices);
    }

}
