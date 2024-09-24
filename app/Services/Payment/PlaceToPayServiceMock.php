<?php

namespace App\Services\Payment;

use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;

class PlaceToPayServiceMock implements PlaceToPayServiceInterface
{
    private string $createPaymentFile = 'created_payment.json';
    private string $checkPaymentFile = 'approved_payment.json';
    private string $createSubscriptionFile = 'created_subscription.json';
    private string $checkSubscriptionFile = 'approved_subscription.json';
    private string $sessionType = 'payment';

    private int $statusCode = 200;

    public function createPayment(Customer $customer, Payment $payment): array
    {
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$this->createPaymentFile")), true);

        return [
            'success' => $this->statusCode === 200,
            'data' => $data,
            'message' => $this->statusCode === 200 ?
                'Payment created successfully' :
                'Failed to create payment',
        ];
    }

    public function createSubscription(Customer $customer, Subscription $subscription): array
    {
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$this->createSubscriptionFile")), true);

        return [
            'success' => $this->statusCode === 200,
            'data' => $data,
            'message' => $this->statusCode === 200 ?
                'Subscription created successfully' :
                'Failed to create subscription',
        ];
    }

    public function checkSession(string $sessionId): array
    {
        $file = $this->sessionType === 'subscription' ? $this->checkSubscriptionFile : $this->checkPaymentFile;
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$file")), true);

        return [
            'success' => $this->statusCode === 200,
            'data' => $data,
            'message' => $this->statusCode === 200 ?
                ucfirst($this->sessionType) . ' status checked successfully' :
                'Failed to check ' . $this->sessionType . ' status',
        ];
    }

    public function setSessionType(string $type): void
    {
        if (!in_array($type, ['payment', 'subscription'])) {
            throw new \InvalidArgumentException("Invalid session type: $type");
        }

        $this->sessionType = $type;
    }

    public function setCreatePaymentInformation(string $file): void
    {
        $this->createPaymentFile = $file;
    }

    public function setCheckPaymentInformation(string $file): void
    {
        $this->checkPaymentFile = $file;
    }

    public function setCreateSubscriptionInformation(string $file): void
    {
        $this->createSubscriptionFile = $file;
    }

    public function setCheckSubscriptionInformation(string $file): void
    {
        $this->checkSubscriptionFile = $file;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
