<?php

namespace App\Http\Controllers\SubscriptionPayment;

use App\Constants\SubscriptionStatus;
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
use Illuminate\Support\Facades\Cache;
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

        $subscriptionData['currency'] = $microsite->payment_currency->value;
        $subscriptionData['plan_id'] = $plan->id;
        $subscriptionData['total_duration'] = $plan->total_duration;
        $subscriptionData['time_unit'] = $plan->time_unit->value;

        $result = $this->subscriptionService->createSubscription($subscriptionData);

        if ($result['success']) {
            return Inertia::location($result['url']);
        } else {
            return redirect()->route('subscription-payments.show', $microsite->slug)
                ->withErrors([
                    'payment' => $result['message'],
                ]);
        }
    }

    public function return(Subscription $subscription): Response|RedirectResponse
    {
        Log::withContext([
            'customer_subscription_id' => $subscription->id,
            'request_id' => $subscription->request_id,
        ]);

        $cacheKey = 'subscription_status_' . $subscription->id;
        $cachedStatus = Cache::get($cacheKey);

        if ($subscription->status === SubscriptionStatus::PENDING->value) {
            if (!$cachedStatus) {
                $result = $this->subscriptionService->checkSubscription($subscription);

                if (!$result['success']) {
                    return Inertia::render('Payments/Return', [
                        'error' => $result['message'],
                    ]);
                }

                $subscription = $result['subscription'];

                Cache::put($cacheKey, $subscription->status, now()->addMinutes(10));
            } else {
                $subscription->status = $cachedStatus; // Use the cached status
            }
        }

        return Inertia::render('Payments/Return', [
            'subscription' => $subscription,
            'customer' => $subscription->customer->only(['name', 'last_name']),
        ]);
    }
}
