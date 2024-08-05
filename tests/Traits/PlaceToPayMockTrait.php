<?php

namespace Tests\Traits;

use App\Contracts\PlaceToPayServiceInterface;
use App\Services\Payment\PlaceToPayServiceMock;

trait PlaceToPayMockTrait
{
    public function fakeCreatePaymentSuccess(): void
    {
        $this->createPlaceToPayMock('created_payment.json', 'createPayment');
    }

    public function fakeCreatePaymentFailed(): void
    {
        $this->createPlaceToPayMock('failed_payment.json', 'createPayment', 400);
    }

    public function fakeCheckApprovedPayment(): void
    {
        $this->createPlaceToPayMock('approved_payment.json', 'checkPayment');
    }

    public function fakeCheckRejectedPayment(): void
    {
        $this->createPlaceToPayMock('rejected_payment.json', 'checkPayment');
    }

    public function fakeCheckPendingPayment(): void
    {
        $this->createPlaceToPayMock('pending_payment.json', 'checkPayment');
    }

    public function fakeCheckFailedPayment(): void
    {
        $this->createPlaceToPayMock('failed_payment.json', 'checkPayment', 400);
    }

    private function createPlaceToPayMock(string $file, string $method, int $statusCode = 200): void
    {
        $this->app->bind(PlaceToPayServiceInterface::class, function () use ($file, $statusCode, $method) {
            $mock = new PlaceToPayServiceMock();
            match ($method) {
                'createPayment' => $mock->setCreatePaymentInformation($file),
                'checkPayment' => $mock->setCheckPaymentInformation($file),
            };

            $mock->setStatusCode($statusCode);

            return $mock;
        });
    }

}
