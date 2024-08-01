<?php

namespace App\Services\Payment;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Models\Guest;
use App\Models\Payment;
use App\Services\PlaceToPayService;
use Illuminate\Support\Str;

class PaymentService implements PaymentServiceInterface
{
    public function createPayment(array $paymentData): array
    {
        /** @var Guest $guest */
        $guest = Guest::query()->firstOrCreate(
            ['document_number' => $paymentData['document_number']],
            [
                'name' => $paymentData['name'],
                'last_name' => $paymentData['last_name'],
                'document_type' => $paymentData['document_type'],
                'document_number' => $paymentData['document_number'],
                'phone' => $paymentData['phone'],
                'email' => $paymentData['email'],
            ]
        );

        /** @var Payment $payment */
        $payment = $guest->payments()->create([
            'microsite_id' => $paymentData['microsite_id'],
            'description' => $paymentData['payment_description'],
            'reference' => date('ymdHis').'-'.strtoupper(Str::random(4)),
            'currency' => $paymentData['currency'],
            'amount' => $paymentData['amount'],
        ]);

        $result = (new PlaceToPayService)->buyer($guest->toArray())
            ->prepare()
            ->payment($payment->toArray())
            ->createPayment();

        $payment->update([
            'request_id' => $result->json()['requestId'],
            'status' => $result->json()['status']['status'],
            'status_message' => $result->json()['status']['message'],
        ]);

        return [
            'success' => $result->ok(),
            'url' => $result['processUrl'] ?? null,
            'message' => $result['status']['message'] ?? null,
            'microsite_slug' => $payment->microsite->slug,
        ];
    }

    public function checkPayment(Payment $payment): array
    {
        $result = (new PlaceToPayService)->prepare()->checkPayment($payment->request_id);

        if ($result->ok()) {
            $this->updatePayment($payment, $result->json());

            return [
                'success' => true,
                'payment' => $payment->refresh(),
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['status']['message'],
            ];
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
