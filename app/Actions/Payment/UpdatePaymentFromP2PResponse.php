<?php

namespace App\Actions\Payment;

use App\Constants\PaymentStatus;
use App\Models\Payment;

class UpdatePaymentFromP2PResponse
{
    public function execute(Payment $payment, array $result): void
    {
        $data = $result['data'];
        $status = $data['status'];

        if ($status['status'] !== PaymentStatus::APPROVED->value) {
            $payment->update([
                'status_message' => $status['message'],
                'status' => $status['status'],
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
            'status' => $transactionStatus['status'],
        ]);
    }
}
