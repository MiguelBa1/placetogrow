<?php

namespace Tests\Feature\Jobs;

use App\Constants\SubscriptionStatus;
use App\Jobs\NotifySubscriptionExpirationJob;
use App\Mail\SubscriptionExpirationReminderMail;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifySubscriptionExpirationJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_email_for_expiring_subscriptions(): void
    {
        Mail::fake();

        $daysBeforeExpiration = 7;
        config()->set('subscriptions.notification.days_before_expiration', $daysBeforeExpiration);

        $subscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'end_date' => Carbon::today()->addDays($daysBeforeExpiration),
        ]);

        (new NotifySubscriptionExpirationJob())->handle();

        Mail::assertQueued(SubscriptionExpirationReminderMail::class, function ($mail) use ($subscription) {
            return $mail->hasTo($subscription->customer->email) &&
                $mail->subscription->is($subscription);
        });
    }

    public function test_it_does_not_send_email_for_inactive_subscriptions(): void
    {
        Mail::fake();

        $daysBeforeExpiration = 7;
        config()->set('subscriptions.notification.days_before_expiration', $daysBeforeExpiration);

        Subscription::factory()->create([
            'status' => SubscriptionStatus::INACTIVE->value,
            'end_date' => Carbon::today()->addDays($daysBeforeExpiration),
        ]);

        (new NotifySubscriptionExpirationJob())->handle();

        Mail::assertNothingSent();
    }

    public function test_it_does_not_send_email_if_subscription_not_expiring_soon(): void
    {
        Mail::fake();

        $daysBeforeExpiration = 7;
        config()->set('subscriptions.notification.days_before_expiration', $daysBeforeExpiration);

        Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'end_date' => Carbon::today()->addDays($daysBeforeExpiration + 3),
        ]);

        (new NotifySubscriptionExpirationJob())->handle();

        Mail::assertNothingSent();
    }
}
