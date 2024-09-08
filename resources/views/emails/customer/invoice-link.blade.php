@component('mail::message')
# {{ __('customer_invoices.access_invoices') }}

{{ __('customer_invoices.click_to_view') }}

@component('mail::button', ['url' => $url])
    {{ __('customer_invoices.view_invoices') }}
@endcomponent

{{ __('customer_invoices.trouble_clicking') }}

[{{ $url }}]({{ $url }})

{{ __('customer_invoices.thanks') }},<br>
{{ config('app.name') }}
@endcomponent
