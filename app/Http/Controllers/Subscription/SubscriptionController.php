<?php

namespace App\Http\Controllers\Subscription;

use App\Constants\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\CancelSubscriptionRequest;
use App\Http\Requests\Subscription\SendSubscriptionLinkRequest;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Mail\ActiveSubscriptionsLinkMail;
use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
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

        Mail::to($customer->email)->send(new ActiveSubscriptionsLinkMail($url));

        return redirect()->back();
    }

    public function show(Request $request, string $email, string $documentNumber): Response
    {
        if (! $request->hasValidSignature()) {
            abort(403, __('message.invalid_link'));
        }

        /** @var Customer $customer */
        $customer = Customer::query()->where('email', $email)
            ->where('document_number', $documentNumber)
            ->firstOrFail();

        $subscriptions = Subscription::where('customer_id', $customer->id)
            ->where('status', SubscriptionStatus::ACTIVE)
            ->with('plan.microsite')
            ->get();

        $subscriptionsResource = SubscriptionResource::collection($subscriptions);

        return Inertia::render('CustomerSubscriptions/Show', [
            'subscriptions' => $subscriptionsResource,
            'customer' => [
                'email' => $email,
                'document_number' => $documentNumber,
            ]
        ]);
    }

    public function cancel(CancelSubscriptionRequest $request, int $subscriptionId): RedirectResponse
    {
        $customer = Customer::where('email', $request->get('email'))
            ->where('document_number', $request->get('document_number'))
            ->firstOrFail();

        $customerSubscription = Subscription::where('id', $subscriptionId)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $customerSubscription->update([
            'status' => SubscriptionStatus::INACTIVE,
        ]);

        return redirect()->back();
    }
}
