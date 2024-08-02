<?php

namespace App\Services\Payment;

use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Guest;
use App\Models\Payment;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Http\Client\Response;

class PlaceToPayServiceMock implements PlaceToPayServiceInterface
{
    private string $createPaymentFile = 'create_payment.json';

    private string $checkPaymentFile = 'approved_payment.json';

    private int $statusCode = 200;

    public function createPayment(Guest $guest, Payment $payment): Response
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

    public function setCreatePaymentInformation(string $file): void
    {
        $this->createPaymentFile = $file;
    }

    public function setCheckPaymentInformation(string $file): void
    {
        $this->checkPaymentFile = $file;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
