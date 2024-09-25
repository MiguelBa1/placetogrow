<?php

namespace App\Http\Controllers\SubscriptionPayment;

use App\Contracts\SubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPayment\CreateSubscriptionPaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Http\Resources\Plan\PlanDetailResource;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionPaymentController extends Controller
{
    private SubscriptionServiceInterface $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function show(Microsite $microsite): Response
    {
        $micrositeData = [
            'id' => $microsite->id,
            'name' => $microsite->name,
            'logo' => $microsite->getFirstMediaUrl('logos'),
            'slug' => $microsite->slug,
            'type' => $microsite->type,
            'payment_currency' => $microsite->payment_currency,
        ];

        $fields = MicrositeFieldDetailResource::collection(
            $microsite->fields()->with('translations')->get()
        );

        $subscriptions = PlanDetailResource::collection(
            $microsite->plans()
                ->with('translations')
                ->get()
        );

        return Inertia::render('Payments/Show', [
            'microsite' => $micrositeData,
            'fields' => $fields,
            'subscriptions' => $subscriptions,
        ]);
    }

    public function store(CreateSubscriptionPaymentRequest $request, Microsite $microsite, Plan $plan): \Symfony\Component\HttpFoundation\Response
    {
        $subscriptionData = $request->validated();
        $subscriptionData['additional_data'] = [];

        foreach ($microsite->fields as $field) {
            if ($field->modifiable) {
                $subscriptionData['additional_data'][$field->name] = $subscriptionData[$field->name] ?? null;
            }
        }

        $result = $this->subscriptionService->createSubscription($plan, $microsite, $subscriptionData);

        if (!$result['success']) {
            return back()->withErrors([
                    'payment' => $result['message'],
                ]);
        }

        return Inertia::location($result['url']);
    }

    public function return(Subscription $subscription): Response|RedirectResponse
    {
        Log::withContext([
            'customer_subscription_id' => $subscription->id,
            'request_id' => $subscription->request_id,
        ]);

        $isSuccessful = $this->subscriptionService->checkSubscription($subscription);

        if (!$isSuccessful) {
            return Inertia::render('Payments/Return', [
                'error' => __('subscription.payment_failed'),
            ]);
        }

        return Inertia::render('Payments/Return', [
            'subscription' => $subscription,
            'customer' => $subscription->customer->only(['name', 'last_name']),
        ]);
    }
}
