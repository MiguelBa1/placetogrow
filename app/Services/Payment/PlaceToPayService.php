<?php

namespace App\Services\Payment;

use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlaceToPayService implements PlaceToPayServiceInterface
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

    public function buyer(Customer $data): self
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

    public function payer(Customer $data): self
    {
        $this->data['payer'] = [
            'name' => $data['name'],
            'surname' => $data['last_name'],
            'email' => $data['email'],
            'documentType' => $data['document_type'],
            'document' => $data['document_number'],
            'mobile' => $data['phone'],
        ];

        return $this;
    }

    public function payment(Payment $data): self
    {
        $this->data['payment'] = [
            'reference' => $data['reference'],
            'description' => $data['description'],
            'amount' => [
                'currency' => $data['currency'],
                'total' => $data['amount'],
            ],
        ];

        $this->data['returnUrl'] = route('basic-payments.return', $data['reference']);

        return $this;
    }

    public function subscription(Subscription $data): self
    {
        $this->data['subscription'] = [
            'reference' => $data->reference,
            'description' => $data->description,
        ];

        $this->data['returnUrl'] = route('subscription-payments.return', $data->reference);

        return $this;
    }

    public function instrument(string $token): self
    {
        $this->data['instrument'] = [
            'token' => [
                'token' => decrypt($token),
            ],
        ];

        return $this;
    }

    public function createPayment(Customer $customer, Payment $payment): array
    {
        $this->prepare();
        $this->payment($payment);
        $this->buyer($customer);

        Log::info('PlaceToPayService: Creating payment', array_merge($this->data['payment'], $this->data['buyer']));

        return $this->handleHttpRequest('/api/session');
    }

    public function createSubscription(Customer $customer, Subscription $subscription): array
    {
        $this->prepare();
        $this->subscription($subscription);
        $this->buyer($customer);

        Log::info('PlaceToPayService: Creating subscription', array_merge($this->data['subscription'], $this->data['buyer']));

        return $this->handleHttpRequest('/api/session');
    }

    public function collectSubscriptionPayment(Customer $customer, Subscription $subscription, Payment $payment): array
    {
        $this->prepare();
        $this->payer($customer);
        $this->instrument($subscription->token);
        $this->payment($payment);

        Log::info('PlaceToPayService: Collecting subscription payment', array_merge($this->data['payment'], $this->data['instrument']));

        return $this->handleHttpRequest('/api/collect');
    }

    public function cancelSubscription(string $subscriptionToken): array
    {
        $this->prepare();
        $this->instrument($subscriptionToken);

        return $this->handleHttpRequest('/api/instrument/invalidate');
    }

    public function checkSession(string $sessionId): array
    {
        $this->prepare();

        return $this->handleHttpRequest('/api/session/' . $sessionId);
    }

    private function handleHttpRequest(string $endpoint): array
    {
        try {
            $response = Http::post($this->config['url'] . $endpoint, $this->data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('PlaceToPayService: Request failed', [
                'response' => $response->json(),
                'status_code' => $response->status(),
            ]);

            return [
                'success' => false,
                'message' => __('placetopay.request_failed'),
                'error' => $response->json(),
            ];
        } catch (Exception $e) {
            Log::error('PlaceToPayService: Error during HTTP request', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('placetopay.error_occurred'),
            ];
        }
    }
}
