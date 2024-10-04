<?php

namespace App\Jobs;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\UpdatePaymentFromP2PResponse;
use App\Constants\SubscriptionStatus;
use App\Contracts\PlaceToPayServiceInterface;
use App\Models\Subscription;
use DateInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class CollectSubscriptionPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $subscriptionId;

    protected ?int $maxRetries;
    protected ?string $retryBackoff;

    public function __construct(int $subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function tries(): int
    {
        $this->loadMicrositeSettings();
        return $this->maxRetries ?? 3;
    }

    protected function loadMicrositeSettings(): void
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::select('id', 'plan_id')
            ->with([
                'plan:id,microsite_id',
                'plan.microsite:id,settings',
            ])
            ->find($this->subscriptionId);

        $microsite = $subscription->plan->microsite;
        $settings = $microsite->settings;

        $this->maxRetries = $settings['retry']['max_retries'] ?? 3;
        $this->retryBackoff = $settings['retry']['retry_backoff'] ?? '1 hour';
    }

    public function handle(
        PlaceToPayServiceInterface $placeToPayService,
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

            $this->loadMicrositeSettings();
            $backoffInSeconds = $this->getBackoffInSeconds();

            $this->release($backoffInSeconds);
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

    protected function getBackoffInSeconds(): int
    {
        $this->loadMicrositeSettings();

        $backoffTimestamp = strtotime($this->retryBackoff, 0);

        return $backoffTimestamp !== false ? $backoffTimestamp : 3600;
    }

    public function failed(?Throwable $exception): void
    {
        Log::error("Failed after max retries for subscription: {$this->subscriptionId}", [
            'error' => $exception->getMessage(),
        ]);

        $subscription = Subscription::find($this->subscriptionId);

        if ($subscription) {
            $subscription->update([
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => 'Payment failed after maximum retries',
            ]);
        }
    }

    public function getSubscriptionId(): int
    {
        return $this->subscriptionId;
    }
}
