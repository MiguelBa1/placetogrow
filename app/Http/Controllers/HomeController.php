<?php

namespace App\Http\Controllers;

use App\Http\Requests\Home\FilterMicrositesRequest;
use App\Models\Category;
use App\Models\Microsite;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(FilterMicrositesRequest $request): Response
    {
        $categories = Category::query()->select('id', 'name')
            ->with('media')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'logo' => $category->getFirstMediaUrl('logos')
                ];
            });

        $categoryFilter = $request->input('category');
        $searchFilter = $request->input('search');

        $micrositesQuery = Microsite::query()->select('id', 'name', 'slug', 'category_id')
            ->with('media')
            ->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('category_id', $categoryFilter);
            })
            ->when($searchFilter, function ($query, $searchFilter) {
                return $query->where('name', 'like', '%' . $searchFilter . '%');
            });

        $microsites = $micrositesQuery->paginate(10)->withQueryString();

        $microsites->getCollection()->transform(function ($microsite) {
            return [
                'id' => $microsite->id,
                'name' => $microsite->name,
                'slug' => $microsite->slug,
                'logo' => $microsite->getFirstMediaUrl('logos')
            ];
        });

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
