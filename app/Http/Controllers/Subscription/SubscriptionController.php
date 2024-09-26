<?php

namespace App\Http\Controllers\Subscription;

use App\Constants\TimeUnit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\CreateSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Http\Resources\Subscription\SubscriptionListResource;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(Microsite $microsite): Response
    {
        $subscriptions = Subscription::withTrashed()
            ->where('microsite_id', $microsite->id)
            ->select(
                'id',
                'price',
                'total_duration',
                'billing_frequency',
                'time_unit',
                'created_at',
                'deleted_at',
            )
            ->with('translations:subscription_id,locale,name')
            ->get();

        $microsite = $microsite->only('id', 'slug', 'name');

        $subscriptions = SubscriptionListResource::collection($subscriptions);

        return Inertia::render('Subscriptions/Index', [
            'microsite' => $microsite,
            'subscriptions' => $subscriptions,
        ]);
    }

    public function create(Microsite $microsite): Response
    {
        $microsite = $microsite->only('id', 'slug');

        return Inertia::render('Subscriptions/Create', [
            'microsite' => $microsite,
            'timeUnits' => TimeUnit::toSelectArray(),
        ]);
    }

    public function store(CreateSubscriptionRequest $request, Microsite $microsite): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($microsite, $validated) {
            /** @var Subscription $subscription */
            $subscription = $microsite->subscriptions()->create($validated);

            foreach ($validated['translations'] as $translation) {
                $subscription->translations()->create($translation);
            }
        });

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }

    public function edit(Microsite $microsite, Subscription $subscription): Response
    {
        $microsite = $microsite->only('id', 'slug');

        $subscription = $subscription
            ->load('translations:subscription_id,locale,name,description')
            ->only('id', 'price', 'total_duration', 'billing_frequency', 'time_unit', 'translations');

        return Inertia::render('Subscriptions/Edit', [
            'microsite' => $microsite,
            'subscription' => $subscription,
            'timeUnits' => TimeUnit::toSelectArray(),
        ]);
    }

    public function update(UpdateSubscriptionRequest $request, Microsite $microsite, Subscription $subscription): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($subscription, $validated) {
            $subscription->update($validated);

            foreach ($validated['translations'] as $translationData) {
                $subscription->translations()
                    ->updateOrCreate(
                        ['locale' => $translationData['locale']],
                        $translationData
                    );
            }
        });

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }

    public function destroy(Microsite $microsite, Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }

    public function restore(Microsite $microsite, Subscription $subscription): RedirectResponse
    {
        $subscription->restore();

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }
}
