@component('mail::message')
# {{ __('subscription.subscription_created_mail.greeting', ['name' => $subscription->customer->name]) }}

{{ __('subscription.subscription_created_mail.message', [
    'plan' => __('subscription.plans.' . $subscription->plan->translations->first()->name),
    'microsite' => $subscription->plan->microsite->name
]) }}

{{ __('subscription.subscription_created_mail.farewell') }}

{{ __('subscription.subscription_created_mail.team', ['microsite' => $subscription->plan->microsite->name]) }}
@endcomponent
