<?php

namespace App\Http\Controllers\Payment;

use App\Constants\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
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
        $micrositeData = (new micrositeService)->getMicrositeData($microsite);
        $documentTypes = DocumentType::toSelectArray();

        return Inertia::render('Payments/Show', [
            'microsite' => $micrositeData,
            'documentTypes' => $documentTypes,
        ]);
    }

    public function store(CreatePaymentRequest $request, Microsite $microsite): RedirectResponse|Response
    {
        $paymentData = $request->validated();
        return (new PaymentService)->createPayment($paymentData, $request->ip(), $request->userAgent(), $microsite);
    }

    public function return(Microsite $microsite, string $reference): \Inertia\Response|RedirectResponse
    {
        return (new PaymentService)->checkPayment($reference, $microsite->slug);
    }
}
