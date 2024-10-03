<?php

namespace App\Jobs;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\UpdatePaymentFromP2PResponse;
use App\Constants\SubscriptionStatus;
use App\Models\Subscription;
use App\Services\PlaceToPayService;
use DateInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectSubscriptionPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $subscriptionId;

    public function __construct(int $subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function handle(
        PlaceToPayService $placeToPayService,
        CreatePaymentAction $createPaymentAction,
        UpdatePaymentFromP2PResponse $updatePaymentFromP2PResponse
    ): void {
        /** @var Subscription $subscription */
        $subscription = Subscription::select(
            'id',
            'plan_id',
            'customer_id',
            'price',
            'token',
            'reference',
            'additional_data',
            'next_payment_date',
            'billing_frequency',
            'time_unit'
        )
            ->where('id', $this->subscriptionId)
            ->with([
                'customer:id,name,last_name,email,document_type,document_number,phone',
                'plan:id,microsite_id',
                'plan.microsite:id,type,payment_currency',
            ])
            ->firstOrFail();

        $customer = $subscription->customer;

        $paymentData = [
            'plan_id' => $subscription->plan_id,
            'payment_description' => 'Subscription payment ' . $subscription->reference,
            'amount' => $subscription->price,
            'additional_data' => $subscription->additional_data,
        ];

        $payment = $createPaymentAction->execute($customer, $subscription->plan->microsite, $paymentData);

        $result = $placeToPayService->collectSubscriptionPayment($customer, $subscription, $payment);

        if (!$result['success']) {
            Log::error("Failed to collect payment for subscription: {$subscription->reference}", [
                'subscription_id' => $subscription->id,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
            return;
        }

        $updatePaymentFromP2PResponse->execute($payment, $result);

        $subscription->update([
            'status' => SubscriptionStatus::ACTIVE->value,
            'next_payment_date' => $subscription
                ->next_payment_date
                ->add(
                    DateInterval::createFromDateString(
                        "{$subscription->billing_frequency} {$subscription->time_unit->value}"
                    )
                ),
        ]);

        Log::info("Payment successfully collected for subscription: {$subscription->reference}", [
            'subscription_id' => $subscription->id,
        ]);
    }
}
