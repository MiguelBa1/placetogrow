<?php

namespace App\Services\Payment;

use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService implements PaymentServiceInterface
{

    private PlaceToPayServiceInterface $placeToPayService;

    public function __construct(PlaceToPayServiceInterface $placeToPayService)
    {
        $this->placeToPayService = $placeToPayService;
    }

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
            'invoice_id' => $paymentData['invoice_id'] ?? null,
            'description' => $paymentData['payment_description'],
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'currency' => $paymentData['currency'],
            'amount' => $paymentData['amount'],
            'additional_data' => $paymentData['additional_data'],
        ]);

        $result = $this->placeToPayService->createPayment($customer, $payment);

        if (!$result->ok()) {

            $payment->update([
                'status' => PaymentStatus::REJECTED->value,
                'status_message' => $result->json()['status']['message'],
            ]);

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
        ];
    }

    public function checkPayment(Payment $payment): array
    {
        $result = $this->placeToPayService->checkPayment($payment->request_id);

        if ($result->ok()) {
            $payment = $this->updatePayment($payment, $result->json());

            return [
                'success' => true,
                'payment' => $payment,
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

            if ($payment->microsite->type === MicrositeType::INVOICE && $payment->invoice) {
                $payment->invoice->update([
                    'status' => InvoiceStatus::PAID->value,
                ]);
            }

        } else {
            $payment->update([
                'status_message' => $response['status']['message'],
                'status' => $response['status']['status'],
            ]);
        }

        return $payment;
    }
}
