<?php

namespace App\Http\Controllers\Payment;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Factories\PaymentDataProviderFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Models\Microsite;
use App\Models\Payment;
use App\Services\MicrositeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
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

        $paymentData = $paymentDataProvider->getPaymentData($request->validated(), Collection::make($microsite->fields));

        $paymentData['currency'] = $microsite->payment_currency->value;
        $paymentData['microsite_id'] = $microsite->id;

        $result = $this->paymentService->createPayment($paymentData);

        if ($result['success']) {
            return Inertia::location($result['url']);
        } else {
            return redirect()->route('payments.show', $microsite->slug)
                ->withErrors([
                    'payment' => $result['message'],
                ]);
        }
    }

    public function return(Payment $payment): \Inertia\Response|RedirectResponse
    {
        Log::withContext([
            'payment_id' => $payment->id,
            'request_id' => $payment->request_id,
        ]);

        if ($payment->status->value === PaymentStatus::PENDING->value) {
            $result = $this->paymentService->checkPayment($payment);

            if (!$result['success']) {
                return redirect()->route('payments.show', $payment->microsite->slug)
                    ->withErrors([
                        'payment' => $result['message'],
                    ]);
            }

            /** @var Payment $payment */
            $payment = $result['payment']; // updated payment
        }

        return Inertia::render('Payments/Return', [
            'payment' => $payment,
            'customerName' => $payment->customer->name . ' ' . $payment->customer->last_name,
            'micrositeName' => $payment->microsite->name,
        ]);
    }
}
