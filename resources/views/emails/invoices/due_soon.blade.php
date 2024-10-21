@component('mail::message')
# {{ __('invoices.due_soon_mail.title') }}

{{ __('invoices.due_soon_mail.greeting', ['name' => $invoice->name]) }}

{{ __('invoices.due_soon_mail.body', [
    'amount' => number_format($invoice->amount, 2),
    'currency' => 'USD', // Cambia esto según sea necesario
    'date' => $invoice->expiration_date->toFormattedDateString(),
    'microsite' => $invoice->microsite->name,
    'reference' => $invoice->reference,
]) }}

@component('mail::button', ['url' => route('home')])
    {{ __('invoices.due_soon_mail.button') }}
@endcomponent

{{ __('invoices.due_soon_mail.thank_you') }},
{{ config('app.name') }}
@endcomponent
