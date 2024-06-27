<?php

namespace App\Http\Controllers;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Http\Requests\Microsite\CreateMicrositeRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Models\Category;
use App\Models\Microsite;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class MicrositeController extends Controller
{
    public function index(): Response
    {
        $microsites = Microsite::with('category:id,name')
            ->select(
                'id',
                'name',
                'category_id',
                'type',
                'slug',
                'responsible_name',
                'payment_currency',
                'payment_expiration'
            )->paginate(10)->onEachSide(1);

        return Inertia::render('Microsites/Index', [
            'microsites' => fn () => $microsites,
        ]);
    }

    public function show(Microsite $microsite): Response
    {
        $documentTypes = DocumentType::toSelectArray();

        $micrositeData = $microsite->only(['id', 'name', 'slug', 'payment_currency']);
        $micrositeData['logo'] = $microsite->getFirstMediaUrl('logos');

        return Inertia::render('Microsites/Show', [
            'microsite' => $micrositeData,
            'documentTypes' => $documentTypes,
        ]);
    }

    public function create(): Response
    {
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentType::toSelectArray();
        $micrositeTypes = MicrositeType::toSelectArray();
        $currencyTypes = CurrencyType::toSelectArray();

        return Inertia::render('Microsites/Create', [
            'categories' => $categories,
            'documentTypes' => $documentTypes,
            'micrositeTypes' => $micrositeTypes,
            'currencyTypes' => $currencyTypes,
        ]);
    }

    public function store(CreateMicrositeRequest $request): HttpFoundationResponse
    {
        $microsite = Microsite::create($request->except('logo'));

        if ($request->hasFile('logo')) {
            $microsite
                ->addMediaFromRequest('logo')
                ->toMediaCollection('logos');
        }

        return back();
    }

    public function edit(Microsite $microsite): Response
    {
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentType::toSelectArray();
        $micrositeTypes = MicrositeType::toSelectArray();
        $currencyTypes = CurrencyType::toSelectArray();

        $microsite->load('category:id,name');
        $micrositeData = $microsite->only(['id', 'name', 'category_id', 'type', 'payment_currency', 'payment_expiration', 'responsible_name', 'responsible_document_number', 'responsible_document_type']);
        $micrositeData['logo'] = $microsite->getFirstMediaUrl('logos');

        return Inertia::render('Microsites/Edit', [
            'microsite' => $micrositeData,
            'categories' => $categories,
            'documentTypes' => $documentTypes,
            'micrositeTypes' => $micrositeTypes,
            'currencyTypes' => $currencyTypes,
        ]);
    }

    public function update(UpdateMicrositeRequest $request, Microsite $microsite): HttpFoundationResponse
    {
        $microsite->update($request->except('logo'));

        if ($request->hasFile('logo')) {
            try {
                $microsite
                    ->addMediaFromRequest('logo')
                    ->toMediaCollection('logos');
            } catch (FileDoesNotExist | FileIsTooBig $e) {
                return back()->withErrors(['logo' => trans('messages.error.uploading_logo')]);
            }
        }

        return back();
    }

    public function destroy(Microsite $microsite): HttpFoundationResponse
    {
        $microsite->delete();

        return back();
    }
}
