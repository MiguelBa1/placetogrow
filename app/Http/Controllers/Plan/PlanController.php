<?php

namespace App\Http\Controllers\Plan;

use App\Constants\PolicyName;
use App\Constants\TimeUnit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\CreatePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Http\Resources\Plan\PlanListResource;
use App\Models\Microsite;
use App\Models\Plan;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Microsite $microsite): Response
    {
        $this->authorize(PolicyName::VIEW_ANY->value, Plan::class);

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
            ->with('translations:plan_id,locale,name')
            ->get();

        $microsite = $microsite->only('id', 'slug', 'name');

        $plans = PlanListResource::collection($plans);

        return Inertia::render('Plans/Index', [
            'microsite' => $microsite,
            'plans' => $plans,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(Microsite $microsite): Response
    {
        $this->authorize(PolicyName::CREATE->value, Plan::class);

        $microsite = $microsite->only('id', 'slug');

        return Inertia::render('Plans/Create', [
            'microsite' => $microsite,
            'timeUnits' => TimeUnit::toSelectArray(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(CreatePlanRequest $request, Microsite $microsite): RedirectResponse
    {
        $this->authorize(PolicyName::CREATE->value, Plan::class);

        $validated = $request->validated();

        DB::transaction(function () use ($microsite, $validated) {
            /** @var Plan $plan */
            $plan = $microsite->plans()->create($validated);

            foreach ($validated['translations'] as $translation) {
                $plan->translations()->create($translation);
            }
        });

        return redirect()->route('microsites.plans.index', $microsite);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Microsite $microsite, Plan $plan): Response
    {
        $this->authorize(PolicyName::UPDATE->value, $plan);

        $microsite = $microsite->only('id', 'slug');

        $plan = $plan
            ->load('translations:plan_id,locale,name,description')
            ->only('id', 'price', 'total_duration', 'billing_frequency', 'time_unit', 'translations');

        return Inertia::render('Plans/Edit', [
            'microsite' => $microsite,
            'plan' => $plan,
            'timeUnits' => TimeUnit::toSelectArray(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdatePlanRequest $request, Microsite $microsite, Plan $plan): RedirectResponse
    {
        $this->authorize(PolicyName::UPDATE->value, $plan);

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

        return redirect()->route('microsites.plans.index', $microsite);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Microsite $microsite, Plan $plan): RedirectResponse
    {
        $this->authorize(PolicyName::DELETE->value, $plan);

        $plan->delete();

        return redirect()->route('microsites.plans.index', $microsite);
    }

    /**
     * @throws AuthorizationException
     */
    public function restore(Microsite $microsite, Plan $plan): RedirectResponse
    {
        $this->authorize(PolicyName::RESTORE->value, $plan);

        $plan->restore();

        return redirect()->route('microsites.plans.index', $microsite);
    }
}
