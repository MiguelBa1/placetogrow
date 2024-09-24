<?php

namespace App\Services\Payment;

use App\Actions\Customer\StoreCustomerAction;
use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Contracts\PlaceToPayServiceInterface;
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
        $customerData = (new StoreCustomerAction())->execute($paymentData);

        /** @var Payment $payment */
        $payment = $customerData->payments()->create([
            'microsite_id' => $paymentData['microsite_id'],
            'invoice_id' => $paymentData['invoice_id'] ?? null,
            'description' => $paymentData['payment_description'],
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'currency' => $paymentData['currency'],
            'amount' => $paymentData['amount'],
            'additional_data' => $paymentData['additional_data'],
        ]);

        $result = $this->placeToPayService->createPayment($customerData, $payment);

        if (!$result['success']) {
            $payment->update([
                'status' => PaymentStatus::REJECTED->value,
                'status_message' => $result['message'],
            ]);

            return [
                'success' => false,
                'message' => $result['message'],
            ];
        }

        $resultData = $result['data'];

        $payment->update([
            'request_id' => $resultData['requestId'],
            'status' => PaymentStatus::PENDING->value,
            'status_message' => $resultData['status']['message'],
        ]);

        return [
            'success' => true,
            'url' => $resultData['processUrl'],
            'message' => $resultData['status']['message'],
        ];
    }


    public function checkPayment(Payment $payment): array
    {
        $result = $this->placeToPayService->checkSession($payment->request_id);

        if (!$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'],
            ];
        }

        $this->updatePayment($payment, $result['data']);

        return [
            'success' => true,
        ];
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
    }
}
