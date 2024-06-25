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

    public function createPayment(array $paymentData, string $ipAddress, string $userAgent): RedirectResponse|Response
    {
        $paymentReference = Str::random();

        $login = config('placetopay.login');
        $secretKey = config('placetopay.tranKey');
        $seed = Carbon::now()->toIso8601String();
        $rawNonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $rawNonce . $seed . $secretKey, true));
        $nonce = base64_encode($rawNonce);

        $data = [
            'auth' => [
                'login' => $login,
                'tranKey' => $tranKey,
                'seed' => $seed,
                'nonce' => $nonce,
            ],
            'buyer' => [
                'name' => $paymentData['name'],
                'surname' => $paymentData['lastName'],
                'email' => $paymentData['email'],
                'document' => $paymentData['documentNumber'],
                'documentType' => $paymentData['documentType'],
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
            'returnUrl' => route('site1.return', $paymentReference),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];

        $result = Http::post(env('P2P_URL') . '/api/session', $data);

        if ($result->ok()) {
            $this->createPaymentRecord($paymentData, $paymentReference, $result->json());

            return Inertia::location($result['processUrl']);
        } else {
            return Redirect::to(route('site1'))
                ->withErrors(
                    $result['status']['message'] ?? 'An error occurred while processing the payment, please try again.'
                );
        }
    }

    public function checkPayment(string $reference): \Inertia\Response|RedirectResponse
    {
        $login = config('placetopay.login');
        $secretKey = config('placetopay.tranKey');
        $seed = Carbon::now()->toIso8601String();
        $rawNonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $rawNonce . $seed . $secretKey, true));
        $nonce = base64_encode($rawNonce);

        $data = [
            'auth' => [
                'login' => $login,
                'tranKey' => $tranKey,
                'seed' => $seed,
                'nonce' => $nonce,
            ],
        ];

        $payment = Payment::query()->where('payment_reference', $reference)->latest()->first();

        if (!$payment) {
            return Redirect::to(route('site1'))->withErrors('Payment not found.');
        }

        $result = Http::post(env('P2P_URL') . '/api/session/' . $payment->request_id, $data);

        if ($result->ok()) {
            $this->updatePayment($reference, $result->json());

            return Inertia::render('Payment/Return', [
                'payment' => $payment->refresh(),
            ]);
        } else {
            return Redirect::to(route('site1'))
                ->withErrors(
                    $result['status']['message'] ?? 'An error occurred while completing the payment.'
                );
        }
    }

    public function createPaymentRecord(array $paymentData, string $paymentReference, array $response): void
    {
        $guestUser = Guest::query()->create([
            'name' => $paymentData['name'],
            'last_name' => $paymentData['lastName'],
            'document_type' => $paymentData['documentType'],
            'document_number' => $paymentData['documentNumber'],
            'phone' => $paymentData['phone'],
            'email' => $paymentData['email'],
        ]);

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
