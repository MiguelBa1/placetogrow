<?php

namespace App\Http\Controllers\SubscriptionPayment;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPayment\CreateSubscriptionPaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Http\Resources\Subscription\SubscriptionDetailResource;
use App\Models\Microsite;
use App\Services\SubscriptionService;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionPaymentController extends Controller
{
    private SubscriptionService $subscriptionService;

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

    public function store(CreateSubscriptionPaymentRequest $request, Microsite $microsite): \Symfony\Component\HttpFoundation\Response
    {
        $subscriptionData = $request->validated();
        $subscriptionData['additional_data'] = [];

        foreach ($microsite->fields as $field) {
            if ($field->modifiable) {
                $subscriptionData['additional_data'][$field->name] = $subscriptionData[$field->name] ?? null;
            }
        }

        $subscriptionData['currency'] = $microsite->payment_currency->value;
        $subscriptionData['microsite_id'] = $microsite->id;

        $result = $this->subscriptionService->createSubscription($subscriptionData);

        if ($result['success']) {
            return Inertia::location($result['url']);
        } else {
            return redirect()->route('payments.show', $microsite->slug)
                ->withErrors([
                    'payment' => $result['message'],
                ]);
        }
    }

    public function return(Microsite $microsite)
    {
        // Return the subscription payment
    }
}
