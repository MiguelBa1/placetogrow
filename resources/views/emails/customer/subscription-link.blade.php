@component('mail::message')
# {{ __('subscription.access_link_mail.greeting') }}

{{ __('subscription.access_link_mail.message') }}

@component('mail::button', ['url' => $url])
    {{ __('subscription.access_link_mail.button_text') }}
@endcomponent

{{ __('subscription.access_link_mail.alt_message') }}

[{{ $url }}]({{ $url }})

{{ __('subscription.access_link_mail.thanks') }}<br>
{{ config('app.name') }}
@endcomponent
