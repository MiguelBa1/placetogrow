<?php

namespace App\Http\Controllers;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Http\Requests\CreateMicrositeRequest;
use App\Models\Category;
use App\Models\Microsite;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class MicrositeController extends Controller
{
    public function index(): Response
    {
        $microsites = Microsite::with('category:id,name,logo')
            ->select(
                'id',
                'name',
                'category_id',
                'type',
                'responsible_name',
                'payment_currency',
                'payment_expiration'
            )->paginate(10);

        return Inertia::render('Microsites/Index', [
            'microsites' => fn () => $microsites,
        ]);
    }

    public function show(Microsite $microsite): Response
    {
        $microsite->load('category:id,name');

        $micrositeData = $microsite->only(['id', 'name', 'logo', 'category_id', 'type', 'payment_currency', 'payment_expiration']);
        $micrositeData['category'] = $microsite->category;

        return Inertia::render('Microsites/Show', [
            'microsite' => $micrositeData,
        ]);
    }

    public function create(): Response
    {
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentType::cases();
        $micrositeTypes = MicrositeType::cases();
        $currencyTypes = CurrencyType::cases();

        return Inertia::render('Microsites/Create', [
            'categories' => $categories,
            'documentTypes' => $documentTypes,
            'micrositeTypes' => $micrositeTypes,
            'currencyTypes' => $currencyTypes,
        ]);
    }

    public function store(CreateMicrositeRequest $request): HttpFoundationResponse
    {
        Microsite::query()->create($request->all());

        return to_route('microsites.index');
    }

    public function edit(Microsite $microsite): Response
    {
        $categories = Category::query()->select('id', 'name')->get();

        return Inertia::render('Microsites/Edit', [
            'microsite' => $microsite,
            'categories' => $categories,
        ]);
    }

    public function update(CreateMicrositeRequest $request, Microsite $microsite): HttpFoundationResponse
    {
        $microsite->update($request->validated());

        return to_route('microsites.index');
    }

    public function destroy(Microsite $microsite): HttpFoundationResponse
    {
        $microsite->delete();

        return back();
    }
}
