@component('mail::message')
# {{ __('invoices.import.mail.title') }}

{{ __('invoices.import.mail.success') }}

@if($failures->isNotEmpty())
## {{ __('invoices.import.mail.failures_title') }}
<ul>
    @foreach($failures as $failure)
        <li>{{ __('invoices.import.mail.failure', ['row' => $failure['row'], 'errors' => implode(', ', $failure['errors'])]) }}</li>
    @endforeach
</ul>
@else
{{ __('invoices.import.mail.no_failures') }}
@endif
@endcomponent
