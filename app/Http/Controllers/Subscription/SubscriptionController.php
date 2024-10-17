<?php

namespace App\Http\Controllers\Subscription;

use App\Constants\SubscriptionStatus;
use App\Contracts\SubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\CancelSubscriptionRequest;
use App\Http\Requests\Subscription\SendSubscriptionLinkRequest;
use App\Http\Resources\Subscription\CustomerResource;
use App\Http\Resources\Subscription\SubscriptionListResource;
use App\Mail\ActiveSubscriptionsLinkMail;
use App\Models\Customer;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    private SubscriptionServiceInterface $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(): Response
    {
        return Inertia::render('Subscriptions/Index');
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
        if (!$request->hasValidSignature()) {
            abort(403, __('message.invalid_link'));
        }

        /** @var Customer $customer */
        $customer = Customer::select(
            'id',
            'email',
            'name',
            'last_name',
            'document_type',
            'document_number',
            'phone',
        )
            ->where('email', $email)
            ->where('document_number', $documentNumber)
            ->firstOrFail();

        $subscriptions = Subscription::select(
            'id',
            'plan_id',
            'start_date',
            'end_date',
            'status',
            'currency',
        )
            ->where('customer_id', $customer->id)
            ->where('status', SubscriptionStatus::ACTIVE)
            ->with('plan:id,price,microsite_id')
            ->with('plan.translations:plan_id,name,locale')
            ->with('plan.microsite:id,name')
            ->orderBy('start_date', 'desc')
            ->get();

        return Inertia::render('Subscriptions/Show', [
            'subscriptions' => SubscriptionListResource::collection($subscriptions),
            'customer' => new CustomerResource($customer),
        ]);
    }

    public function cancel(CancelSubscriptionRequest $request, int $subscriptionId): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Customer::select('id')
            ->where('email', $request->get('email'))
            ->where('document_number', $request->get('document_number'))
            ->firstOrFail();

        /** @var Subscription $subscription */
        $subscription = Subscription::select('id', 'token', 'customer_id')
            ->where('id', $subscriptionId)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $isCancelled = $this->subscriptionService->cancelSubscription($subscription);

        if (!$isCancelled) {
            return back()->withErrors([
                'cancel' => __('subscription_payment.cancel_failed'),
            ]);
        }

        return back();
    }
}
