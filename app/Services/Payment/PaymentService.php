<?php

namespace App\Services\Payment;

use App\Actions\Customer\StoreCustomerAction;
use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\UpdatePaymentFromP2PResponse;
use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PaymentService implements PaymentServiceInterface
{
    private PlaceToPayServiceInterface $placeToPayService;

    public function __construct(PlaceToPayServiceInterface $placeToPayService)
    {
        $this->placeToPayService = $placeToPayService;
    }

    public function createPayment(Microsite $microsite, array $paymentData): array
    {
        $customer = (new StoreCustomerAction())->execute($paymentData);

        $payment = (new CreatePaymentAction())->execute($customer, $microsite, $paymentData);

        $result = $this->placeToPayService->createPayment($customer, $payment);

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
        Log::withContext([
            'payment_id' => $payment->id,
            'request_id' => $payment->request_id,
        ]);

        $cacheKey = 'payment_checked_' . $payment->id;
        $isRecentlyChecked = Cache::get($cacheKey);

        if ($isRecentlyChecked || $payment->status->value !== PaymentStatus::PENDING->value) {
            return true;
        }

        $result = $this->placeToPayService->checkSession($payment->request_id);

        if (!$result['success']) {
            return false;
        }

        $this->updatePayment($payment, $result);

        Cache::put($cacheKey, true, now()->addMinutes(10));

        return true;
    }

    public function updatePayment(Payment $payment, array $data): void
    {
        (new UpdatePaymentFromP2PResponse())->execute($payment, $data);

        if ($payment->microsite->type === MicrositeType::INVOICE && $payment->invoice) {
            $payment->invoice->update([
                'status' => InvoiceStatus::PAID->value,
            ]);
        }
    }
}
