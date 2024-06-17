<?php

namespace App\Http\Controllers;

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
        $microsites = Microsite::with('category:id,name')
            ->select('id', 'name', 'logo', 'category_id', 'type', 'payment_currency', 'payment_expiration')
            ->get();

        return Inertia::render('Microsites/Index', [
            'microsites' => $microsites,
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
        $categories = Category::select('id', 'name')->get();

        return Inertia::render('Microsites/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(CreateMicrositeRequest $request): HttpFoundationResponse
    {
        Microsite::create($request->validated());

        return Inertia::location(route('microsites.index'));
    }

    public function edit(Microsite $microsite): Response
    {
        $categories = Category::select('id', 'name')->get();

        return Inertia::render('Microsites/Edit', [
            'microsite' => $microsite,
            'categories' => $categories,
        ]);
    }

    public function update(CreateMicrositeRequest $request, Microsite $microsite): HttpFoundationResponse
    {
        $microsite->update($request->validated());

        return Inertia::location(route('microsites.index'));
    }

    public function destroy(Microsite $microsite): HttpFoundationResponse
    {
        $microsite->delete();

        return Inertia::location(route('microsites.index'));
    }
}
