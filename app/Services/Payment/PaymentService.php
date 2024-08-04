<?php

namespace App\Services\Payment;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService implements PaymentServiceInterface
{
    public function createPayment(array $paymentData): array
    {
        /** @var Customer $customer */
        $customer = Customer::query()->firstOrCreate(
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
        $payment = $customer->payments()->create([
            'microsite_id' => $paymentData['microsite_id'],
            'description' => $paymentData['payment_description'],
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'currency' => $paymentData['currency'],
            'amount' => $paymentData['amount'],
        ]);

        $result = app(PlaceToPayServiceInterface::class)->createPayment($customer, $payment);

        if (!$result->ok()) {
            return [
                'success' => false,
                'message' => $result->json()['status']['message'],
            ];
        }

        $payment->update([
            'request_id' => $result->json()['requestId'],
            'status' => PaymentStatus::PENDING->value,
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
        $result = app(PlaceToPayServiceInterface::class)->checkPayment($payment->request_id);

        if ($result->ok()) {
            $payment = $this->updatePayment($payment, $result->json());

            return [
                'success' => true,
                'payment' => $payment,
                'customerName' => $payment->customer->name . ' ' . $payment->customer->last_name,
                'micrositeName' => $payment->microsite->name,
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['status']['message'],
            ];
        }
    }

    public function updatePayment(Payment $payment, array $response): Payment
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
                'status' => $response['status']['status'],
            ]);
        }

        return $payment;
    }
}
