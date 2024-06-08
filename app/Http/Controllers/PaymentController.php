<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Site1/Index');
    }

    public function store(CreatePaymentRequest $request): RedirectResponse|Response
    {
        return (new PaymentService)->createPayment($request);
    }

    public function return($reference): \Inertia\Response|RedirectResponse
    {
        return (new PaymentService)->checkPayment($reference);
    }
}
