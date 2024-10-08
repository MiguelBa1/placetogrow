<?php

namespace Tests\Traits;

use App\Contracts\PlaceToPayServiceInterface;
use App\Services\Payment\PlaceToPayServiceMock;

trait PlaceToPayMockTrait
{
    public function fakePaymentCreationSuccess(): void
    {
        $this->createPlaceToPayMock('created_payment.json', 'createPayment');
    }

    public function fakePaymentCreationFailed(): void
    {
        $this->createPlaceToPayMock('failed_payment.json', 'createPayment', 400);
    }

    public function fakePaymentCheckApproved(): void
    {
        $this->createPlaceToPayMock('approved_payment.json', 'checkPayment');
    }

    public function fakePaymentCheckRejected(): void
    {
        $this->createPlaceToPayMock('rejected_payment.json', 'checkPayment');
    }

    public function fakePaymentCheckPending(): void
    {
        $this->createPlaceToPayMock('pending_payment.json', 'checkPayment');
    }

    public function fakePaymentCheckFailed(): void
    {
        $this->createPlaceToPayMock('failed_payment.json', 'checkPayment', 400);
    }

    public function fakeSubscriptionCreationSuccess(): void
    {
        $this->createPlaceToPayMock('created_subscription.json', 'createSubscription');
    }

    public function fakeSubscriptionCreationFailed(): void
    {
        $this->createPlaceToPayMock('failed_subscription.json', 'createSubscription', 400);
    }

    public function fakeSubscriptionCheckApproved(): void
    {
        $this->createPlaceToPayMock('approved_subscription.json', 'checkSubscription');
    }

    public function fakeSubscriptionCheckRejected(): void
    {
        $this->createPlaceToPayMock('rejected_subscription.json', 'checkSubscription');
    }

    public function fakeSubscriptionCheckPending(): void
    {
        $this->createPlaceToPayMock('pending_subscription.json', 'checkSubscription');
    }

    public function fakeSubscriptionCheckFailed(): void
    {
        $this->createPlaceToPayMock('failed_subscription.json', 'checkSubscription', 400);
    }
    private function createPlaceToPayMock(string $file, string $method, int $statusCode = 200): void
    {
        $this->app->bind(PlaceToPayServiceInterface::class, function () use ($file, $statusCode, $method) {
            $mock = new PlaceToPayServiceMock();
            match ($method) {
                'createPayment' => $mock->setCreatePaymentInformation($file),
                'checkPayment' => $mock->setCheckPaymentInformation($file),
                'createSubscription' => $mock->setCreateSubscriptionInformation($file),
                'checkSubscription' => $mock->setCheckSubscriptionInformation($file),
            };

            $mock->setStatusCode($statusCode);

            return $mock;
        });
    }

}
