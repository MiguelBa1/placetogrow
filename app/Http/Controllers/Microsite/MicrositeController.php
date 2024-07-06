<?php

namespace App\Http\Controllers\Microsite;

use App\Actions\Microsite\DestroyMicrositeAction;
use App\Actions\Microsite\RestoreMicrositeAction;
use App\Actions\Microsite\StoreMicrositeAction;
use App\Actions\Microsite\UpdateMicrositeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Microsite\CreateMicrositeRequest;
use App\Http\Requests\Microsite\FilterMicrositesRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Models\Microsite;
use App\Services\MicrositeService;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class MicrositeController extends Controller
{
    public function index(FilterMicrositesRequest $request, MicrositeService $micrositeService): Response
    {
        $searchFilter = $request->input('search');
        $categoryFilter = $request->input('category');

        $microsites = $micrositeService->getAllMicrosites($searchFilter, $categoryFilter);

        $categories = $micrositeService->getFormData()['categories'];

        return Inertia::render('Microsites/Index', [
            'microsites' => fn () => $microsites,
            'categories' => fn () => $categories,
            'filters' => fn () => [
                'search' => $searchFilter,
                'category' => $categoryFilter,
            ],
        ]);
    }

    public function show(Microsite $microsite): Response
    {
        $micrositeData = (new micrositeService)->getMicrositeData($microsite);

        return Inertia::render('Microsites/Show', [
            'microsite' => $micrositeData,
        ]);
    }

    public function create(): Response
    {
        $formData = (new micrositeService)->getFormData();

        return Inertia::render('Microsites/Create', $formData);
    }

    public function store(CreateMicrositeRequest $request, StoreMicrositeAction $storeMicrositeAction): HttpFoundationResponse
    {
        $storeMicrositeAction->execute($request);

        return back();
    }

    public function edit(Microsite $microsite): Response
    {
        $formData = (new micrositeService)->getFormData();
        $micrositeData = (new micrositeService)->getEditData($microsite);

        return Inertia::render('Microsites/Edit', array_merge($formData, [
            'microsite' => $micrositeData,
        ]));
    }

    public function update(UpdateMicrositeRequest $request, Microsite $microsite, UpdateMicrositeAction $updateMicrositeAction): HttpFoundationResponse
    {
        try {
            $updateMicrositeAction->execute($request, $microsite);
        } catch (Exception $e) {
            return back()->withErrors(['logo' => $e->getMessage()]);
        }

        return redirect()->route('microsites.edit', $microsite);
    }

    public function destroy(Microsite $microsite, DestroyMicrositeAction $destroyMicrositeAction): HttpFoundationResponse
    {
        $destroyMicrositeAction->execute($microsite);

        return back();
    }

    public function restore(string $slug, RestoreMicrositeAction $restoreMicrositeAction): HttpFoundationResponse
    {
        $restoreMicrositeAction->execute($slug);

        return back();
    }
}
