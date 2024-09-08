@component('mail::message')
# {{ __('subscription.mail.greeting') }}

{{ __('subscription.mail.message') }}

@component('mail::button', ['url' => $url])
    {{ __('subscription.mail.button_text') }}
@endcomponent

{{ __('subscription.mail.alt_message') }}

[{{ $url }}]({{ $url }})

{{ __('subscription.mail.thanks') }}<br>
{{ config('app.name') }}
@endcomponent
