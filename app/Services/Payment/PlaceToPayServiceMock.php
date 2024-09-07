<?php

namespace App\Services\Payment;

use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Customer;
use App\Models\Payment;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Client\Response;

class PlaceToPayServiceMock implements PlaceToPayServiceInterface
{
    private string $createPaymentFile = 'created_payment.json';

    private string $checkPaymentFile = 'approved_payment.json';

    private string $createSubscriptionFile = 'created_subscription.json';

    private string $checkSubscriptionFile = 'approved_subscription.json';

    private int $statusCode = 200;

    public function createPayment(Customer $customer, Payment $payment): Response
    {
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$this->createPaymentFile")), true);

        $guzzleResponse = new GuzzleResponse($this->statusCode, ['Content-Type' => 'application/json'], json_encode($data));
        return new Response($guzzleResponse);
    }

    public function checkPayment(string $sessionId): Response
    {
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$this->checkPaymentFile")), true);

        $guzzleResponse = new GuzzleResponse($this->statusCode, ['Content-Type' => 'application/json'], json_encode($data));
        return new Response($guzzleResponse);
    }

    public function createSubscription(Customer $customer, Pivot $subscriptionPivot): Response
    {
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$this->createSubscriptionFile")), true);

        $guzzleResponse = new GuzzleResponse($this->statusCode, ['Content-Type' => 'application/json'], json_encode($data));
        return new Response($guzzleResponse);
    }

    public function checkSubscription(string $sessionId): Response
    {
        $data = json_decode(file_get_contents(app_path("../tests/Stubs/$this->checkSubscriptionFile")), true);

        $guzzleResponse = new GuzzleResponse($this->statusCode, ['Content-Type' => 'application/json'], json_encode($data));
        return new Response($guzzleResponse);
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
