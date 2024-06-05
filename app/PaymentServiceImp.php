<?php

namespace App;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentServiceImp implements PaymentService
{
    public function createPayment(Request $request)
    {
        $referenciaDePago = Str::random();

        $login = env('P2P_LOGIN');
        $secretKey = env('P2P_SECRET_KEY');
        $seed = Carbon::now()->toIso8601String();
        $rawNonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $rawNonce . $seed . $secretKey, true));
        $nonce = base64_encode($rawNonce);

        $datos = [
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
                'reference' => $referenciaDePago,
                'description' => 'Payment test',
                'amount' => [
                    'currency' => $request->input('currency'),
                    'total' => $request->input('amount'),
                ],
            ],
            'expiration' => Carbon::now()->addMinutes(10)->toIso8601String(),
            'returnUrl' => route('site1.return', $referenciaDePago),
            'ipAddress' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ];

        $resultado = Http::post(env('P2P_URL') . '/api/session', $datos);

        if ($resultado->ok()) {
            $this->createUser($request, $referenciaDePago, $resultado->json());

            return Inertia::location($resultado['processUrl']);
        } else {
            return Redirect::to('/site1')
                ->withErrors(
                    $resultado['status']['message'] ?? 'Ha ocurrido un error al procesar el pago, por favor intente nuevamente.'
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

        $datos = [
            'auth' => [
                'login' => $login,
                'tranKey' => $tranKey,
                'seed' => $seed,
                'nonce' => $nonce,
            ],
        ];

        $pago = User::query()->where('payment_reference', $reference)->latest()->first();

        if (!$pago) {
            return Redirect::to('/site1')->withErrors('No se encontró el pago solicitado.');
        }

        $resultado = Http::post(env('P2P_URL') . '/api/session/' . $pago->request_id, $datos);

        if ($resultado->ok()) {
            $this->updatePayment($reference, $resultado->json());

            return Inertia::render('Site1/Return', [
                'payment' => $pago->refresh(),
            ]);
        } else {
            return Redirect::to('/site1')
                ->withErrors(
                    $resultado['status']['message'] ?? 'Ha ocurrido un error al finalizar el pago'
                );
        }
    }

    public function createUser($request, $paymentReference, $response)
    {
        User::query()->create([
            'email' => $request->input('email'),
            'nombre' => $request->input('name'),
            'apellido' => $request->input('lastName'),
            'tipo_de_documento' => $request->input('documentType'),
            'numero_de_documento' => $request->input('documentNumber'),
            'telefono' => $request->input('phone'),
            'moneda' => $request->input('currency'),
            'valor' => $request->input('amount'),
            'payment_reference' => $paymentReference,
            'request_id' => $response['requestId'],
            'process_url' => $response['processUrl'],
            'status' => $response['status']['status'],
            'status_message' => $response['status']['message'],
            'expires_in' => Carbon::create($response['status']['date'])->addMinutes(10),
        ])->save();
    }

    public function updatePayment($paymentReference, $response)
    {
        $pago = User::query()->where('payment_reference', $paymentReference)->latest()->first();

        if ($response['status']['status'] === 'APPROVED') {
            $pago->update([
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
                $pago->update([
                    'status_message' => $response['payment'][0]['status']['message'],
                    'payment_date' => $response['payment'][0]['status']['date'],
                    'status' => $response['status']['status'],
                ]);
            } else {
                $pago->update([
                    'status_message' => $response['status']['message'],
                    'payment_date' => $response['status']['date'],
                    'status' => $response['status']['status'],
                ]);
            }
        }
    }
}
