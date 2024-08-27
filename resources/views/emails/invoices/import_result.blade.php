<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{ __('invoices.import.mail.title') }}</title>
    </head>
    <body>
        <h1>{{ __('invoices.import.mail.title') }}</h1>
        <p>{{ __('invoices.import.mail.success', ['count' => $successCount]) }}</p>

        @if($failures->isNotEmpty())
            <h2>{{ __('invoices.import.mail.failures_title') }}</h2>
            <ul>
                @foreach($failures as $failure)
                    <li>{{ __('invoices.import.mail.failure', ['row' => $failure['row'], 'errors' => implode(', ', $failure['errors'])]) }}</li>
                @endforeach
            </ul>
        @else
            <p>{{ __('invoices.import.mail.no_failures') }}</p>
        @endif
    </body>
</html>
