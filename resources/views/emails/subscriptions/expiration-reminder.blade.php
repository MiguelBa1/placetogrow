@component('mail::message')
# {{ __('subscription.expiration_reminder.greeting', ['name' => $subscription->customer->name]) }}

{{ __('subscription.expiration_reminder.body', [
    'plan' => $subscription->plan->translations()->where('locale', app()->getLocale())->first()->name,
    'microsite' => $subscription->plan->microsite->name,
    'end_date' => $subscription->end_date->format('d-m-Y')
]) }}

{{ __('subscription.expiration_reminder.renew_cta') }}

@component('mail::button', ['url' => route('home')])
    {{ __('subscription.expiration_reminder.button_text') }}
@endcomponent

{{ __('subscription.expiration_reminder.thank_you') }}

{{ __('subscription.expiration_reminder.signature', ['microsite' => $subscription->plan->microsite->name]) }}
@endcomponent
