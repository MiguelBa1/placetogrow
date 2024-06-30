<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Models\Microsite;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function store(CreatePaymentRequest $request, Microsite $microsite): RedirectResponse|Response
    {
        $paymentData = $request->validated();
        return (new PaymentService)->createPayment($paymentData, $request->ip(), $request->userAgent(), $microsite->slug);
    }

    public function return(Microsite $microsite, string $reference): \Inertia\Response|RedirectResponse
    {
        return (new PaymentService)->checkPayment($reference, $microsite->slug);
    }
}
