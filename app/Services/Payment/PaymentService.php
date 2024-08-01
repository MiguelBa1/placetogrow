<?php

namespace App\Services\Payment;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Models\Guest;
use App\Models\Payment;
use App\Services\PlaceToPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentService implements PaymentServiceInterface
{
    public function createPayment(array $paymentData): Response
    {
        $paymentReference = Str::random();

        /** @var Guest $guest */
        $guest = Guest::query()->firstOrCreate(
            ['document_number' => $paymentData['document_number']],
            [
                'name' => $paymentData['name'],
                'last_name' => $paymentData['last_name'],
                'document_type' => $paymentData['document_type'],
                'phone' => $paymentData['phone'],
                'email' => $paymentData['email'],
            ]
        );

        /** @var Payment $payment */
        $payment = $guest->payments()->create([
            'microsite_id' => $paymentData['microsite_id'],
            'description' => $paymentData['payment_description'],
            'reference' => $paymentReference,
            'currency' => $paymentData['currency'],
            'amount' => $paymentData['amount'],
        ]);

        $data = [
            'buyer' => [
                'name' => $guest->name,
                'surname' => $guest->last_name,
                'email' => $guest->email,
                'document' => $guest->document_number,
                'documentType' => $guest->document_type,
                'mobile' => $guest->phone,
            ],
            'payment' => [
                'reference' => $payment->reference,
                'description' => $payment->description,
                'amount' => [
                    'currency' => $payment->currency,
                    'total' => $payment->amount,
                ],
            ],
            'expiration' => Carbon::now()->addMinutes(10)->toIso8601String(),
            'returnUrl' => route('payments.return', $payment->reference),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
        ];

        $result = (new PlaceToPayService)->createPayment($data);

        $payment->update([
            'request_id' => $result->json()['requestId'],
            'status' => $result->json()['status']['status'],
            'status_message' => $result->json()['status']['message'],
        ]);

        if ($result->ok()) {
            return Inertia::location($result['processUrl']);
        } else {
            return redirect(route('payments.show', $payment->microsite->slug))
                ->withErrors($result['status']['message']);
        }
    }

    public function checkPayment(Payment $payment): \Inertia\Response|RedirectResponse
    {
        $result = (new PlaceToPayService)->checkPayment($payment->request_id);

        if ($result->ok()) {
            $this->updatePayment($payment, $result->json());

            return Inertia::render('Payments/Return', [
                'payment' => $payment->refresh(),
            ]);
        } else {
            return redirect()->route('payments.show', $payment->microsite->slug)
                ->withErrors($result['status']['message']);
        }
    }

    public function updatePayment(Payment $payment, array $response): void
    {
        if ($response['status']['status'] === PaymentStatus::APPROVED->value) {

            $paymentResponse = $response['payment'][0];

            $payment->update([
                'payment_method_name' => $paymentResponse['paymentMethodName'],
                'authorization' => $paymentResponse['authorization'],
                'payment_date' => $paymentResponse['status']['date'],
                'status_message' => $paymentResponse['status']['message'],
                'status' => $paymentResponse['status']['status'],
            ]);
        } else {
            $payment->update([
                'status_message' => $response['status']['message'],
                'payment_date' => $response['status']['date'],
                'status' => $response['status']['status'],
            ]);
        }
    }
}
