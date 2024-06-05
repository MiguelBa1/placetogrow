<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckPaymentsCommand extends Command
{
    protected $signature = 'app:check-payments';

    protected $description = 'Command to check the status of the payments';

    public function handle()
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

        $payments = User::query()->whereIn('status', ['PENDING', 'OK'])->where('expires_in', '<', Carbon::now())->get();

        foreach ($payments as $payment) {
            $this->info('Checking payment ' . $payment->request_id);

            $resultado = Http::post(env('P2P_URL') . '/api/session/' . $payment->request_id, $datos);

            if ($resultado->ok()) {
                if ($resultado['status']['status'] === 'APPROVED') {
                    $payment->update([
                        'internal_reference' => $resultado['payment'][0]['internalReference'],
                        'franchise' => $resultado['payment'][0]['franchise'],
                        'payment_method' => $resultado['payment'][0]['paymentMethod'],
                        'payment_method_name' => $resultado['payment'][0]['paymentMethodName'],
                        'issuer_name' => $resultado['payment'][0]['issuerName'],
                        'authorization' => $resultado['payment'][0]['authorization'],
                        'receipt' => $resultado['payment'][0]['receipt'],
                        'payment_date' => $resultado['payment'][0]['status']['date'],
                        'status_message' => $resultado['payment'][0]['status']['message'],
                        'status' => $resultado['payment'][0]['status']['status'],
                    ]);
                } else {
                    if (isset($resultado['payment'][0])) {
                        $payment->update([
                            'status_message' => $resultado['payment'][0]['status']['message'],
                            'payment_date' => $resultado['payment'][0]['status']['date'],
                            'status' => $resultado['status']['status'],
                        ]);
                    } else {
                        $payment->update([
                            'status_message' => $resultado['status']['message'],
                            'payment_date' => $resultado['status']['date'],
                            'status' => $resultado['status']['status'],
                        ]);
                    }
                }
            }
        }
    }
}
