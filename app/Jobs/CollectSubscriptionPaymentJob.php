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
        $this->retryBackoff = $settings['retry']['retry_backoff'] ?? 1;
    }

    public function handle(PlaceToPayServiceInterface $placeToPayService): void
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::select(
            'id',
            'plan_id',
            'customer_id',
            'price',
            'token',
            'reference',
            'additional_data',
            'end_date',
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

        $payment = (new CreatePaymentAction())->execute($customer, $subscription->plan->microsite, $paymentData);

        $result = $placeToPayService->collectSubscriptionPayment($customer, $subscription, $payment);

        if (!$result['success']) {
            Log::error("Failed to collect payment for subscription: {$subscription->reference}", [
                'subscription_id' => $subscription->id,
                'error' => $result['message'] ?? 'Unknown error',
            ]);

            $this->loadMicrositeSettings();
            $backoffInSeconds = $this->retryBackoff * 3600;

            $this->release($backoffInSeconds);
            return;
        }

        (new UpdatePaymentFromP2PResponse())->execute($payment, $result);

        $newNextPaymentDate = $subscription->next_payment_date->add(
            DateInterval::createFromDateString(
                "{$subscription->billing_frequency} {$subscription->time_unit->value}"
            )
        );

        $newStatus = $newNextPaymentDate > $subscription->end_date
            ? SubscriptionStatus::INACTIVE->value
            : SubscriptionStatus::ACTIVE->value;

        $statusMessage = $newStatus === SubscriptionStatus::INACTIVE->value
            ? 'Subscription has reached its end date'
            : null;

        $subscription->update([
            'status' => $newStatus,
            'next_payment_date' => $newNextPaymentDate,
            'status_message' => $statusMessage,
        ]);

        if ($newStatus === SubscriptionStatus::INACTIVE->value) {
            Log::info("Subscription reached end date and has been deactivated: {$subscription->reference}", [
                'subscription_id' => $subscription->id,
            ]);
        } else {
            Log::info("Payment successfully collected for subscription: {$subscription->reference}", [
                'subscription_id' => $subscription->id,
            ]);
        }
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
