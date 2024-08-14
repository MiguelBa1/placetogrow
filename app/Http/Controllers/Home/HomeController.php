<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\FilterMicrositesRequest;
use App\Services\HomeService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(FilterMicrositesRequest $request, HomeService $homeService): Response
    {
        $categoryFilter = $request->input('category');
        $searchFilter = $request->input('search');

        $microsites = $homeService->filterMricositesWithCategory($categoryFilter, $searchFilter);
        $categories = $microsites->getCollection()->pluck('category')->unique('id')->values();

        return Inertia::render('Home/Index', [
            'categories' => fn () => $categories,
            'microsites' => fn () => $microsites,
            'filters' => fn () => [
                'category' => $categoryFilter,
                'search' => $searchFilter,
            ],
        ]);
    }
}
