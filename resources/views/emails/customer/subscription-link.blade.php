@component('mail::message')
# Access Your Subscriptions

Click the button below to view your active subscriptions.

@component('mail::button', ['url' => $url])
    View Subscriptions
@endcomponent

If you’re having trouble clicking the "View Subscriptions" button, copy and paste the URL below into your web browser:

[{{ $url }}]({{ $url }})

Thanks,<br>
{{ config('app.name') }}
@endcomponent
