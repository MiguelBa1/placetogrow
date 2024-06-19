<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function store(CreatePaymentRequest $request): RedirectResponse|Response
    {
        $paymentData = $request->validated();
        return (new PaymentService)->createPayment($paymentData, $request->ip(), $request->userAgent());
    }

    public function return($reference): \Inertia\Response|RedirectResponse
    {
        return (new PaymentService)->checkPayment($reference);
    }
}
