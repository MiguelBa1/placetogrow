<?php

namespace App\Http\Controllers\Plan;

use App\Constants\TimeUnit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\CreateSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Http\Resources\Subscription\SubscriptionListResource;
use App\Models\Microsite;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    public function index(Microsite $microsite): Response
    {
        $plans = Plan::withTrashed()
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

        $plans = SubscriptionListResource::collection($plans);

        return Inertia::render('Subscriptions/Index', [
            'microsite' => $microsite,
            'subscriptions' => $plans,
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
            /** @var Plan $plan */
            $plan = $microsite->plans()->create($validated);

            foreach ($validated['translations'] as $translation) {
                $plan->translations()->create($translation);
            }
        });

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }

    public function edit(Microsite $microsite, Plan $plan): Response
    {
        $microsite = $microsite->only('id', 'slug');

        $plan = $plan
            ->load('translations:subscription_id,locale,name,description')
            ->only('id', 'price', 'total_duration', 'billing_frequency', 'time_unit', 'translations');

        return Inertia::render('Subscriptions/Edit', [
            'microsite' => $microsite,
            'subscription' => $plan,
            'timeUnits' => TimeUnit::toSelectArray(),
        ]);
    }

    public function update(UpdateSubscriptionRequest $request, Microsite $microsite, Plan $plan): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($plan, $validated) {
            $plan->update($validated);

            foreach ($validated['translations'] as $translationData) {
                $plan->translations()
                    ->updateOrCreate(
                        ['locale' => $translationData['locale']],
                        $translationData
                    );
            }
        });

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }

    public function destroy(Microsite $microsite, Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }

    public function restore(Microsite $microsite, Plan $plan): RedirectResponse
    {
        $plan->restore();

        return redirect()->route('microsites.subscriptions.index', $microsite);
    }
}
