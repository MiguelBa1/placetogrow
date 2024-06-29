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
        $categories = $homeService->getCategoriesWithLogos();

        $categoryFilter = $request->input('category');
        $searchFilter = $request->input('search');

        $microsites = $homeService->filterMicrosites($categoryFilter, $searchFilter);

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
