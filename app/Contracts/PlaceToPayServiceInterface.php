<?php

namespace App\Contracts;

use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Http\Client\Response;

interface PlaceToPayServiceInterface
{
    public function createPayment(Guest $guest, Payment $payment): Response;

    public function checkPayment(string $sessionId): Response;
}
