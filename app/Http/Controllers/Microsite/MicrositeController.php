<?php

namespace App\Http\Controllers\Microsite;

use App\Actions\Microsite\DestroyMicrositeAction;
use App\Actions\Microsite\RestoreMicrositeAction;
use App\Actions\Microsite\StoreMicrositeAction;
use App\Actions\Microsite\UpdateMicrositeAction;
use App\Actions\Microsite\UpdateMicrositeSettingsAction;
use App\Constants\LateFeeType;
use App\Constants\PolicyName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Microsite\CreateMicrositeRequest;
use App\Http\Requests\Microsite\FilterMicrositesRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Http\Requests\Microsite\UpdateMicrositeSettingsRequest;
use App\Http\Resources\MicrositeField\MicrositeFieldListResource;
use App\Models\Microsite;
use App\Services\MicrositeService;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class MicrositeController extends Controller
{
    private MicrositeService $micrositeService;

    public function __construct(MicrositeService $micrositeService)
    {
        $this->micrositeService = $micrositeService;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(FilterMicrositesRequest $request): Response
    {
        $this->authorize(PolicyName::VIEW_ANY->value, Microsite::class);

        $searchFilter = $request->input('search');
        $categoryFilter = $request->input('category');

        $microsites = $this->micrositeService->getAllMicrosites($searchFilter, $categoryFilter);

        $categories = $this->micrositeService->getFormData()['categories'];

        return Inertia::render('Microsites/Index', [
            'microsites' => fn () => $microsites,
            'categories' => fn () => $categories,
            'filters' => fn () => [
                'search' => $searchFilter,
                'category' => $categoryFilter,
            ],
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Microsite $microsite): Response
    {
        $this->authorize(PolicyName::VIEW->value, $microsite);

        $micrositeData = $this->micrositeService->getMicrositeData($microsite);
        $fields = MicrositeFieldListResource::collection($microsite->fields()->orderBy('created_at', 'desc')->get());

        return Inertia::render('Microsites/Show', [
            'microsite' => $micrositeData,
            'fields' => $fields,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize(PolicyName::CREATE->value, Microsite::class);

        $formData = $this->micrositeService->getFormData();

        return Inertia::render('Microsites/Create', $formData);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(CreateMicrositeRequest $request, StoreMicrositeAction $storeMicrositeAction): HttpFoundationResponse
    {
        $this->authorize(PolicyName::CREATE->value, Microsite::class);

        $storeMicrositeAction->execute($request);

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Microsite $microsite): Response
    {
        $this->authorize(PolicyName::UPDATE->value, $microsite);

        $formData = $this->micrositeService->getFormData();
        $micrositeData = $this->micrositeService->getEditData($microsite);

        return Inertia::render('Microsites/Edit', array_merge($formData, [
            'microsite' => $micrositeData,
            'lateFeeTypes' => LateFeeType::toSelectArray(),
        ]));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateMicrositeRequest $request, Microsite $microsite, UpdateMicrositeAction $updateMicrositeAction): HttpFoundationResponse
    {
        $this->authorize(PolicyName::UPDATE->value, $microsite);

        $result = $updateMicrositeAction->execute($request, $microsite);

        if (!$result['success']) {
            return back()->withErrors([
                'message' => $result['message']
            ]);
        }

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function updateSettings(UpdateMicrositeSettingsRequest $request, Microsite $microsite, UpdateMicrositeSettingsAction $updateMicrositeSettingsAction): HttpFoundationResponse
    {
        $this->authorize(PolicyName::UPDATE->value, $microsite);

        $settings = $request->validated('settings');

        $result = $updateMicrositeSettingsAction->execute($settings, $microsite);

        if (!$result['success']) {
            return back()->withErrors($result['message']);
        }

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Microsite $microsite, DestroyMicrositeAction $destroyMicrositeAction): HttpFoundationResponse
    {
        $this->authorize(PolicyName::DELETE->value, $microsite);

        $destroyMicrositeAction->execute($microsite);

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function restore(string $slug, RestoreMicrositeAction $restoreMicrositeAction): HttpFoundationResponse
    {
        $this->authorize(PolicyName::RESTORE->value, Microsite::class);

        $restoreMicrositeAction->execute($slug);

        return back();
    }
}
