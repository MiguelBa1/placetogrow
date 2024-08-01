<?php

namespace App\Services\Payment;

use App\Contracts\PaymentServiceInterface;
use App\Models\Guest;
use App\Models\Microsite;
use App\Models\Payment;
use App\Services\PlaceToPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentService implements PaymentServiceInterface
{
    public function createPayment(array $paymentData, Microsite $microsite): Response
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
            'returnUrl' => route('payments.return', [
                'reference' => $payment->reference,
                'microsite' => $microsite->slug,
            ]),
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
            return to_route(route('payments.show', $microsite->slug))
                ->withErrors($result['status']['message']);
        }
    }

    public function checkPayment(string $reference, string $micrositeSlug): \Inertia\Response|RedirectResponse
    {
        /** @var Payment $payment */
        $payment = Payment::query()->where('reference', $reference)->first();

        if (!$payment) {
            return to_route(route('payments.show', $micrositeSlug))
                ->withErrors('Payment not found.');
        }

        $result = (new PlaceToPayService)->checkPayment($payment->request_id);

        if ($result->ok()) {
            $this->updatePayment($reference, $result->json());

            return Inertia::render('Payments/Return', [
                'payment' => $payment->refresh(),
            ]);
        } else {
            return Redirect::to(route('payments.show', $micrositeSlug))
                ->withErrors($result['status']['message']);
        }
    }

    public function updatePayment(string $paymentReference, array $response): void
    {
        $payment = Payment::query()->where('payment_reference', $paymentReference)->latest()->first();

        $paymentResponse = $response['payment'][0];

        if ($response['status']['status'] === 'APPROVED') {
            $payment->update([
                'payment_method_name' => $paymentResponse['paymentMethodName'],
                'authorization' => $paymentResponse['authorization'],
                'payment_date' => $paymentResponse['status']['date'],
                'status_message' => $paymentResponse['status']['message'],
                'status' => $paymentResponse['status']['status'],
            ]);
        } else {
            if (isset($paymentResponse)) {
                $payment->update([
                    'status_message' => $paymentResponse['status']['message'],
                    'payment_date' => $paymentResponse['status']['date'],
                    'status' => $response['status']['status'],
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
}
