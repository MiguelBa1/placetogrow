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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
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
        $paymentDataProvider = (new PaymentDataProviderFactory())->create($microsite->type);

        $paymentData = $paymentDataProvider->getPaymentData($request->validated(), Collection::make($microsite->fields));

        $result = $this->paymentService->createPayment($microsite, $paymentData);

        if (!$result['success']) {
            return back()->withErrors([
                    'payment' => $result['message'],
                ]);
        }

        return Inertia::location($result['url']);
    }

    public function return(Payment $payment): \Inertia\Response|RedirectResponse
    {
        Log::withContext([
            'payment_id' => $payment->id,
            'request_id' => $payment->request_id,
        ]);

        $cacheKey = 'payment_status_' . $payment->id;

        $cachedStatus = Cache::get($cacheKey);

        if ($payment->status->value === PaymentStatus::PENDING->value) {
            if (!$cachedStatus) {
                $result = $this->paymentService->checkPayment($payment);

                if (!$result['success']) {
                    return redirect()->route('payments.show', $payment->microsite->slug)
                        ->withErrors([
                            'payment' => $result['message'],
                        ]);
                }

                $payment->refresh();

                Cache::put($cacheKey, $payment->status->value, now()->addMinutes(10));
            } else {
                $payment->status = $cachedStatus; // Use the cached status
            }
        }

        return Inertia::render('Payments/Return', [
            'payment' => $payment,
            'customer' => $payment->customer->only(['name', 'last_name']),
            'micrositeName' => $payment->microsite->name,
        ]);
    }
}
