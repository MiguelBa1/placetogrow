<?php

namespace App\Services;

use App\Contracts\PaymentServiceInterface;
use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentService implements PaymentServiceInterface
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

    public function createPayment(array $paymentData, string $ipAddress, string $userAgent, string $micrositeSlug): RedirectResponse|Response
    {
        $paymentReference = Str::random();

        $authData = $this->generateAuthData();

        $data = [
            'auth' => $authData,
            'buyer' => [
                'name' => $paymentData['name'],
                'surname' => $paymentData['last_name'],
                'email' => $paymentData['email'],
                'document' => $paymentData['document_number'],
                'documentType' => $paymentData['document_type'],
                'mobile' => '+57' . $paymentData['phone'],
            ],
            'payment' => [
                'reference' => $paymentReference,
                'description' => 'Payment test',
                'amount' => [
                    'currency' => $paymentData['currency'],
                    'total' => $paymentData['amount'],
                ],
            ],
            'expiration' => Carbon::now()->addMinutes(10)->toIso8601String(),
            'returnUrl' => route('payments.return', [
                'reference' => $paymentReference,
                'microsite' => $micrositeSlug,
            ]),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];

        $result = Http::post(env('P2P_URL') . '/api/session', $data);

        if ($result->ok()) {
            $this->createPaymentRecord($paymentData, $paymentReference, $result->json());

            return Inertia::location($result['processUrl']);
        } else {
            return Redirect::to(route('payments.show', $micrositeSlug))
                ->withErrors(
                    $result['status']['message'] ?? 'An error occurred while processing the payment, please try again.'
                );
        }
    }

    public function checkPayment(string $reference, string $micrositeSlug): \Inertia\Response|RedirectResponse
    {
        $authData = $this->generateAuthData();

        $data = [
            'auth' => $authData
        ];

        $payment = Payment::query()->where('payment_reference', $reference)->latest()->first();

        if (!$payment) {
            return Redirect::to(route('payments.show', $micrositeSlug))
                ->withErrors('Payment not found.');
        }

        $result = Http::post(env('P2P_URL') . '/api/session/' . $payment->request_id, $data);

        if ($result->ok()) {
            $this->updatePayment($reference, $result->json());

            return Inertia::render('Payments/Return', [
                'payment' => $payment->refresh(),
            ]);
        } else {
            return Redirect::to(route('payments.show', $micrositeSlug))
                ->withErrors(
                    $result['status']['message'] ?? 'An error occurred while completing the payment.'
                );
        }
    }

    public function createPaymentRecord(array $paymentData, string $paymentReference, array $response): void
    {
        $guestUser = Guest::query()->where('email', $paymentData['email'])->get()->first();

        if(! $guestUser->exists()) {
            $guestUser = Guest::query()->create([
                'name' => $paymentData['name'],
                'last_name' => $paymentData['last_name'],
                'document_type' => $paymentData['document_type'],
                'document_number' => $paymentData['document_number'],
                'phone' => $paymentData['phone'],
                'email' => $paymentData['email'],
            ]);
        } else {
            $guestUser =  $guestUser->get()->first();
        }


        Payment::query()->create([
            'guest_id' => $guestUser->id,
            'payment_reference' => $paymentReference,
            'request_id' => $response['requestId'],
            'process_url' => $response['processUrl'],
            'status' => $response['status']['status'],
            'status_message' => $response['status']['message'],
            'expires_in' => Carbon::create($response['status']['date'])->addMinutes(10),
            'currency' => $paymentData['currency'],
            'amount' => $paymentData['amount'],
        ]);
    }

    public function updatePayment(string $paymentReference, array $response): void
    {
        $payment = Payment::query()->where('payment_reference', $paymentReference)->latest()->first();

        if ($response['status']['status'] === 'APPROVED') {
            $payment->update([
                'internal_reference' => $response['payment'][0]['internalReference'],
                'franchise' => $response['payment'][0]['franchise'],
                'payment_method' => $response['payment'][0]['paymentMethod'],
                'payment_method_name' => $response['payment'][0]['paymentMethodName'],
                'issuer_name' => $response['payment'][0]['issuerName'],
                'authorization' => $response['payment'][0]['authorization'],
                'receipt' => $response['payment'][0]['receipt'],
                'payment_date' => $response['payment'][0]['status']['date'],
                'status_message' => $response['payment'][0]['status']['message'],
                'status' => $response['payment'][0]['status']['status'],
            ]);
        } else {
            if (isset($response['payment'][0])) {
                $payment->update([
                    'status_message' => $response['payment'][0]['status']['message'],
                    'payment_date' => $response['payment'][0]['status']['date'],
                    'status' => $response['status']['status'],
                ]);
            } else {
                $payment->update([
                    'status_message' => $response['status']['message'],
                    'payment_date' => $response['status']['date'],
                    'status' => $response['status']['status'],
                ]);
            }
        }
    }
}
