<?php

namespace Tests\Feature\Jobs;

use App\Constants\SubscriptionStatus;
use App\Jobs\NotifyUpcomingSubscriptionChargeJob;
use App\Mail\UpcomingSubscriptionChargeMail;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyUpcomingSubscriptionChargeJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_email_for_upcoming_subscription_charge(): void
    {
        Mail::fake();

        $daysBeforeCharge = 5;
        config()->set('subscription.notification.days_before_charge', $daysBeforeCharge);

        $subscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'next_payment_date' => Carbon::today()->addDays($daysBeforeCharge),
        ]);

        (new NotifyUpcomingSubscriptionChargeJob())->handle();

        Mail::assertQueued(UpcomingSubscriptionChargeMail::class, function ($mail) use ($subscription) {
            return $mail->hasTo($subscription->customer->email) &&
                $mail->subscription->is($subscription);
        });
    }

    public function test_it_does_not_send_email_for_inactive_subscriptions(): void
    {
        Mail::fake();

        $daysBeforeCharge = 5;
        config()->set('subscription.notification.days_before_charge', $daysBeforeCharge);

        Subscription::factory()->create([
            'status' => SubscriptionStatus::INACTIVE->value,
            'next_payment_date' => Carbon::today()->addDays($daysBeforeCharge),
        ]);

        (new NotifyUpcomingSubscriptionChargeJob())->handle();

        Mail::assertNothingSent();
    }

    public function test_it_does_not_send_email_if_next_payment_date_is_not_in_the_specified_range(): void
    {
        Mail::fake();

        $daysBeforeCharge = 5;
        config()->set('subscription.notification.days_before_charge', $daysBeforeCharge);

        Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'next_payment_date' => Carbon::today()->addDays($daysBeforeCharge + 1),
        ]);

        (new NotifyUpcomingSubscriptionChargeJob())->handle();

        Mail::assertNothingSent();
    }
}
