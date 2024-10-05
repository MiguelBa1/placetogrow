<?php

namespace App\Actions\Payment;

use App\Constants\PaymentStatus;
use App\Constants\PlaceToPayStatus;
use App\Models\Payment;
use ValueError;

class UpdatePaymentFromP2PResponse
{
    public function execute(Payment $payment, array $result): void
    {
        $data = $result['data'];
        $status = $data['status'];

        try {
            $mappedStatus = $this->mapPlaceToPayStatusToPaymentStatus(PlaceToPayStatus::from($status['status']));
        } catch (ValueError) {
            $mappedStatus = PaymentStatus::UNKNOWN->value;
        }

        if ($mappedStatus !== PaymentStatus::APPROVED->value) {
            $payment->update([
                'status_message' => $status['message'],
                'status' => $mappedStatus,
            ]);
            return;
        }

        $transaction = $data['payment'][0];
        $transactionStatus = $transaction['status'];

        $payment->update([
            'request_id' => $data['requestId'],
            'payment_method_name' => $transaction['paymentMethodName'],
            'authorization' => $transaction['authorization'],
            'payment_date' => $transactionStatus['date'],
            'status_message' => $transactionStatus['message'],
            'status' => $this->mapPlaceToPayStatusToPaymentStatus(PlaceToPayStatus::from($transactionStatus['status'])),
        ]);
    }

    private function mapPlaceToPayStatusToPaymentStatus(PlaceToPayStatus $placeToPayStatus): string
    {
        return match ($placeToPayStatus) {
            PlaceToPayStatus::APPROVED => PaymentStatus::APPROVED->value,
            PlaceToPayStatus::PENDING => PaymentStatus::PENDING->value,
            PlaceToPayStatus::REJECTED => PaymentStatus::REJECTED->value,
            default => PaymentStatus::UNKNOWN->value,
        };
    }
}
