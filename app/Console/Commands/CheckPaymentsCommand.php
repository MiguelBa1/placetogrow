<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckPaymentsCommand extends Command
{
    protected $signature = 'app:check-payments';

    protected $description = 'Command to check the status of the payments';

    public function handle(): void
    {
        $login = env('P2P_LOGIN');
        $secretKey = env('P2P_SECRET_KEY');
        $seed = Carbon::now()->toIso8601String();
        $rawNonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $rawNonce . $seed . $secretKey, true));
        $nonce = base64_encode($rawNonce);

        $authData = [
            'login' => $login,
            'tranKey' => $tranKey,
            'seed' => $seed,
            'nonce' => $nonce,
        ];

        $payments = Payment::query()->where('status', PaymentStatus::PENDING->value)
            // TODO: replace this with created_at
            //->where('expires_in', '<', Carbon::now())
            ->get();

        foreach ($payments as $payment) {
            $this->info('Checking payment ' . $payment->request_id);

            $result = Http::post(env('P2P_URL') . '/api/session/' . $payment->request_id, ['auth' => $authData]);

            if ($result->ok()) {
                (new PaymentService())->updatePayment($payment->payment_reference, $result->json());
            }
        }
    }
}
