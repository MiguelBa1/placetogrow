<?php

namespace App\Http\Controllers\BasicPayment;

use App\Contracts\PaymentServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Models\Microsite;
use App\Models\Payment;
use App\Services\Payment\BasicPaymentDataProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class BasicPaymentController extends Controller
{
    private PaymentServiceInterface $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Microsite $microsite): \Inertia\Response
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

    public function store(CreatePaymentRequest $request, Microsite $microsite): RedirectResponse|Response
    {
        $basicPaymentDataProvider = new BasicPaymentDataProvider();

        $paymentData = $basicPaymentDataProvider->getPaymentData($request->validated(), Collection::make($microsite->fields));

        $result = $this->paymentService->createPayment($microsite, $paymentData);

        if (!$result['success']) {
            return back()->withErrors([
                    'payment' => __('payment.create_failed'),
                ]);
        }

        return Inertia::location($result['url']);
    }

    public function return(Payment $payment): \Inertia\Response|RedirectResponse
    {
        $isSuccessful = $this->paymentService->checkPayment($payment);

        if (!$isSuccessful) {
            return redirect()->route('basic-payments.show', $payment->microsite->slug)
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
}
