<?php

namespace App\Http\Controllers\Microsite;

use App\Actions\Microsite\DestroyMicrositeAction;
use App\Actions\Microsite\StoreMicrositeAction;
use App\Actions\Microsite\UpdateMicrositeAction;
use App\Constants\DocumentType;
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
    public function index(FilterMicrositesRequest $request): Response
    {
        $searchFilter = $request->input('search');

        $microsites = (new micrositeService)->getAllMicrosites($searchFilter);

        return Inertia::render('Microsites/Index', [
            'microsites' => fn () => $microsites,
            'filters' => fn () => [
                'search' => $searchFilter,
            ],
        ]);
    }

    public function show(Microsite $microsite): Response
    {
        $micrositeData = (new micrositeService)->getMicrositeData($microsite);
        $documentTypes = DocumentType::toSelectArray();

        return Inertia::render('Payments/Show', [
            'microsite' => $micrositeData,
            'documentTypes' => $documentTypes,
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
}
