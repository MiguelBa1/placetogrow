<?php

namespace App\Http\Controllers\CustomerSubscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSubscription\SendSubscriptionLinkRequest;
use App\Http\Resources\CustomerSubscription\CustomerSubscriptionResource;
use App\Mail\CustomerSubscriptionLinkMail;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class CustomerSubscriptionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('CustomerSubscriptions/Index');
    }

    public function sendLink(SendSubscriptionLinkRequest $request): RedirectResponse
    {
        $customer = $request->customer;

        $url = URL::temporarySignedRoute(
            'subscriptions.show',
            now()->addMinutes(60),
            [
                'email' => $customer->email,
                'document_number' => $customer->document_number,
            ]
        );

        Mail::to($customer->email)->send(new CustomerSubscriptionLinkMail($url));

        return redirect()->back()->with('success', 'Subscription link sent successfully.');
    }

    public function show(Request $request, string $email, string $documentNumber): Response
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        /** @var Customer $customer */
        $customer = Customer::query()->where('email', $email)
            ->where('document_number', $documentNumber)
            ->firstOrFail();

        $subscriptions = CustomerSubscription::where('customer_id', $customer->id)
            ->with('subscription.microsite')
            ->get();

        $subscriptionsResource = CustomerSubscriptionResource::collection($subscriptions);

        return Inertia::render('CustomerSubscription/Show', [
            'subscriptions' => $subscriptionsResource,
            'customer' => [
                'email' => $request->get('email'),
                'document_number' => $request->get('document_number'),
            ]
        ]);
    }
}
