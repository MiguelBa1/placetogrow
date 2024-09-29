<?php

namespace App\Services\Payment;

use App\Actions\Customer\StoreCustomerAction;
use App\Actions\Payment\CreatePaymentAction;
use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;

class PaymentService implements PaymentServiceInterface
{
    private PlaceToPayServiceInterface $placeToPayService;

    public function __construct(PlaceToPayServiceInterface $placeToPayService)
    {
        $this->placeToPayService = $placeToPayService;
    }

    public function createPayment(Microsite $microsite, array $paymentData): array
    {
        $customerData = (new StoreCustomerAction())->execute($paymentData);

        $payment = (new CreatePaymentAction())->execute($customerData, $microsite, $paymentData);

        $result = $this->placeToPayService->createPayment($customerData, $payment);

        if (!$result['success']) {
            $payment->update([
                'status' => PaymentStatus::REJECTED->value,
                'status_message' => $result['message'],
            ]);

            return [
                'success' => false,
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
        ];
    }
    public function checkPayment(Payment $payment): bool
    {
        $cacheKey = 'payment_checked_' . $payment->id;
        $isRecentlyChecked = Cache::get($cacheKey);

        if ($isRecentlyChecked || $payment->status->value !== PaymentStatus::PENDING->value) {
            return true;
        }

        $result = $this->placeToPayService->checkSession($payment->request_id);

        if (!$result['success']) {
            return false;
        }

        $this->updatePayment($payment, $result['data']);

        Cache::put($cacheKey, true, now()->addMinutes(10));

        return true;
    }

    public function updatePayment(Payment $payment, array $response): void
    {
        $status = $response['status'];

        if ($status['status'] !== PaymentStatus::APPROVED->value) {
            $payment->update([
                'status_message' => $status['message'],
                'status' => $status['status'],
            ]);
            return;
        }

        $paymentResponse = $response['payment'][0];
        $paymentStatus = $paymentResponse['status'];

        $payment->update([
            'payment_method_name' => $paymentResponse['paymentMethodName'],
            'authorization' => $paymentResponse['authorization'],
            'payment_date' => $paymentStatus['date'],
            'status_message' => $paymentStatus['message'],
            'status' => $paymentStatus['status'],
        ]);

        if ($payment->microsite->type === MicrositeType::INVOICE && $payment->invoice) {
            $payment->invoice->update([
                'status' => InvoiceStatus::PAID->value,
            ]);
        }
    }
}
