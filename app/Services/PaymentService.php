<?php

namespace App\Services;

use App\Contracts\PaymentServiceInterface;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentService implements PaymentServiceInterface
{
    private const SITE1_ROUTE = '/site1';

    public function createPayment(Request $request)
    {
        $paymentReference = Str::random();

        $login = env('P2P_LOGIN');
        $secretKey = env('P2P_SECRET_KEY');
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
                'name' => $request->input('name'),
                'surname' => $request->input('lastName'),
                'email' => $request->input('email'),
                'document' => $request->input('documentNumber'),
                'documentType' => $request->input('documentType'),
                'mobile' => '+57' . $request->input('phone'),
            ],
            'payment' => [
                'reference' => $paymentReference,
                'description' => 'Payment test',
                'amount' => [
                    'currency' => $request->input('currency'),
                    'total' => $request->input('amount'),
                ],
            ],
            'expiration' => Carbon::now()->addMinutes(10)->toIso8601String(),
            'returnUrl' => route('site1.return', $paymentReference),
            'ipAddress' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ];

        $result = Http::post(env('P2P_URL') . '/api/session', $data);

        if ($result->ok()) {
            $this->createPaymentRecord($request, $paymentReference, $result->json());

            return Inertia::location($result['processUrl']);
        } else {
            return Redirect::to(self::SITE1_ROUTE)
                ->withErrors(
                    $result['status']['message'] ?? 'An error occurred while processing the payment, please try again.'
                );
        }
    }

    public function checkPayment(string $reference)
    {
        $login = env('P2P_LOGIN');
        $secretKey = env('P2P_SECRET_KEY');
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
            return Redirect::to(self::SITE1_ROUTE)->withErrors('Payment not found.');
        }

        $result = Http::post(env('P2P_URL') . '/api/session/' . $payment->request_id, $data);

        if ($result->ok()) {
            $this->updatePayment($reference, $result->json());

            return Inertia::render('Site1/Return', [
                'payment' => $payment->refresh(),
            ]);
        } else {
            return Redirect::to(self::SITE1_ROUTE)
                ->withErrors(
                    $result['status']['message'] ?? 'An error occurred while completing the payment.'
                );
        }
    }

    public function createPaymentRecord($request, $paymentReference, $response)
    {
        $user = User::query()->create([
            'name' => $request->input('name'),
            'last_name' => $request->input('lastName'),
            'document_type' => $request->input('documentType'),
            'document_number' => $request->input('documentNumber'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);

        Payment::query()->create([
            'user_id' => $user->id,
            'payment_reference' => $paymentReference,
            'request_id' => $response['requestId'],
            'process_url' => $response['processUrl'],
            'status' => $response['status']['status'],
            'status_message' => $response['status']['message'],
            'expires_in' => Carbon::create($response['status']['date'])->addMinutes(10),
            'currency' => $request->input('currency'),
            'amount' => $request->input('amount'),
        ]);
    }

    public function updatePayment($paymentReference, $response)
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
