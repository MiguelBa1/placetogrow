<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PlaceToPayService
{
    private array $data;

    private array $config;

    public function __construct()
    {
        $this->config = config('placetopay');

        $this->data = [
            'expiration' => $this->config['expiration'],
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
        ];

    }

    public function prepare(): self
    {
        $login = $this->config['login'];
        $secretKey = $this->config['tranKey'];
        $seed = date('c');
        $rawNonce = Str::random();
        $nonce = base64_encode($rawNonce);

        $tranKey = base64_encode(hash('sha256', $nonce . $seed . $secretKey, true));
        $nonce = base64_encode($nonce);

        $this->data['auth'] = [
            'login' => $login,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'seed' => $seed,
        ];

        return $this;
    }

    public function buyer(array $data): self
    {
        $this->data['buyer'] = [
            'name' => $data['name'],
            'surname' => $data['last_name'],
            'email' => $data['email'],
            'documentType' => $data['document_type'],
            'document' => $data['document_number'],
            'mobile' => $data['phone'],
        ];

        return $this;
    }

    public function payment(array $data): self
    {
        $this->data['payment'] = [
            'reference' => $data['reference'],
            'description' => $data['description'],
            'amount' => [
                'currency' => $data['currency'],
                'total' => $data['amount'],
            ],
        ];

        $this->data['returnUrl'] = route('payments.return', $data['reference']);

        return $this;
    }

    public function createPayment(): Response
    {
        return Http::post(config('placetopay.url') . '/api/session', $this->data);
    }

    public function checkPayment(string $sessionId): Response
    {
        return Http::post(config('placetopay.url') . '/api/session/' . $sessionId, [
            'auth' => $this->data['auth'],
        ]);
    }
}
