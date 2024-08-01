<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PlaceToPayService
{

    private function generateAuthData(): array
    {
        $login = config('placetopay.login');
        $secretKey = config('placetopay.tranKey');
        $seed = Carbon::now()->toIso8601String();
        $rawNonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $rawNonce . $seed . $secretKey, true));
        $nonce = base64_encode($rawNonce);

        return [
            'login' => $login,
            'tranKey' => $tranKey,
            'seed' => $seed,
            'nonce' => $nonce,
        ];
    }

    public function createPayment(array $data): Response
    {
        $authData = $this->generateAuthData();

        $data['auth'] = $authData;

        return Http::post(config('placetopay.url') . '/api/session', $data);
    }

    public function checkPayment(string $sessionId): Response
    {
        $authData = $this->generateAuthData();

        return Http::post(config('placetopay.url') . '/api/session/' . $sessionId, [
            'auth' => $authData,
        ]);
    }
}
