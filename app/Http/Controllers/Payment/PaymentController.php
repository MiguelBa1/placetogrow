<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Models\Microsite;
use App\Services\MicrositeService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
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
        $paymentData = $request->validated();
        $paymentData['currency'] = $microsite->payment_currency->value;
        return (new PaymentService)->createPayment($paymentData, $request->ip(), $request->userAgent(), $microsite->slug);
    }

    public function return(Microsite $microsite, string $reference): \Inertia\Response|RedirectResponse
    {
        return (new PaymentService)->checkPayment($reference, $microsite->slug);
    }
}
