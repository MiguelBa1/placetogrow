<?php

namespace App\Http\Controllers\SubscriptionPayment;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPayment\CreateSubscriptionPaymentRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldDetailResource;
use App\Http\Resources\Subscription\SubscriptionDetailResource;
use App\Models\Microsite;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionPaymentController extends Controller
{
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

    public function store(CreateSubscriptionPaymentRequest $request, Microsite $microsite)
    {
        dd($request->toArray());
        // Store the subscription payment
    }

    public function return(Microsite $microsite)
    {
        // Return the subscription payment
    }
}
