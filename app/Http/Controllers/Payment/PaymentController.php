<?php

namespace App\Http\Controllers\Payment;

use App\Contracts\PaymentServiceInterface;
use App\Factories\PaymentDataProviderFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Models\Microsite;
use App\Models\Payment;
use App\Services\MicrositeService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private PaymentServiceInterface $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Microsite $microsite): \Inertia\Response
    {
        $micrositeData = (new MicrositeService)->getMicrositeData($microsite);
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
        $paymentDataProvider = (new PaymentDataProviderFactory())->create($microsite->type);

        $paymentData = $paymentDataProvider->getPaymentData($request->validated());

        $paymentData['currency'] = $microsite->payment_currency->value;
        $paymentData['microsite_id'] = $microsite->id;

        $result = $this->paymentService->createPayment($paymentData);

        if ($result['success']) {
            return Inertia::location($result['url']);
        } else {
            return redirect()->route('payments.show', $result['microsite_slug'])
                ->withErrors($result['message']);
        }
    }

    public function return(Payment $payment): \Inertia\Response|RedirectResponse
    {
        $result = $this->paymentService->checkPayment($payment);

        if ($result['success']) {
            return Inertia::render('Payments/Return', [
                'payment' => $result['payment'],
            ]);
        } else {
            return redirect()->route('payments.show', $payment->microsite->slug)
                ->withErrors($result['message']);
        }
    }
}
