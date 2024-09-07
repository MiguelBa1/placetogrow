<?php

namespace App\Http\Controllers\SubscriptionPayment;

use App\Constants\SubscriptionStatus;
use App\Contracts\SubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPayment\CreateSubscriptionPaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Http\Resources\Subscription\SubscriptionDetailResource;
use App\Models\CustomerSubscription;
use App\Models\Microsite;
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

        $subscriptions = SubscriptionDetailResource::collection(
            $microsite->subscriptions()
                ->with('translations')
                ->get()
        );

        return Inertia::render('Payments/Show', [
            'microsite' => $micrositeData,
            'fields' => $fields,
            'subscriptions' => $subscriptions,
        ]);
    }

    public function store(CreateSubscriptionPaymentRequest $request, Microsite $microsite, Subscription $subscription): \Symfony\Component\HttpFoundation\Response
    {
        $subscriptionData = $request->validated();
        $subscriptionData['additional_data'] = [];

        foreach ($microsite->fields as $field) {
            if ($field->modifiable) {
                $subscriptionData['additional_data'][$field->name] = $subscriptionData[$field->name] ?? null;
            }
        }

        $subscriptionData['currency'] = $microsite->payment_currency->value;
        $subscriptionData['subscription_id'] = $subscription->id;
        $subscriptionData['total_duration'] = $subscription->total_duration;
        $subscriptionData['time_unit'] = $subscription->time_unit->value;

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

    public function return(CustomerSubscription $customerSubscription): Response|RedirectResponse
    {
        Log::withContext([
            'customer_subscription_id' => $customerSubscription->id,
            'request_id' => $customerSubscription->request_id,
        ]);

        $cacheKey = 'subscription_status_' . $customerSubscription->id;
        $cachedStatus = Cache::get($cacheKey);

        if ($customerSubscription->status === SubscriptionStatus::PENDING->value) {
            if (!$cachedStatus) {
                $result = $this->subscriptionService->checkSubscription($customerSubscription);

                if (!$result['success']) {
                    return Inertia::render('Payments/Return', [
                        'error' => $result['message'],
                    ]);
                }

                $customerSubscription = $result['customer_subscription'];

                Cache::put($cacheKey, $customerSubscription->status, now()->addMinutes(10));
            } else {
                $customerSubscription->status = $cachedStatus; // Use the cached status
            }
        }

        return Inertia::render('Payments/Return', [
            'subscription' => $customerSubscription,
            'customer' => $customerSubscription->customer->only(['name', 'last_name']),
        ]);
    }
}
