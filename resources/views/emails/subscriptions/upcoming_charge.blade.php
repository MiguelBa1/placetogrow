@component('mail::message')
# {{ __('subscription.upcoming_charge_mail.title') }}

{{ __('subscription.upcoming_charge_mail.greeting', ['name' => $subscription->customer->name]) }}

{{ __('subscription.upcoming_charge_mail.body', [
    'amount' => number_format($subscription->price, 2),
    'currency' => $subscription->currency,
    'date' => $subscription->next_payment_date->toFormattedDateString(),
]) }}

**{{ __('subscription.upcoming_charge_mail.microsite') }}:** {{ $subscription->plan->microsite->name }}

{{ __('subscription.upcoming_charge_mail.thank_you') }},
{{ config('app.name') }}
@endcomponent
