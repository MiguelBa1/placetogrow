<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HomeService
{
    public function getCategoriesWithLogos(): Collection
    {
        return Category::query()
            ->select('id', 'name')
            ->with('media')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'logo' => asset('images/categories/cat_'. random_int(1, 5) . '.svg'),
                ];
            });
    }

    public function filterMicrosites(?string $categoryFilter, ?string $searchFilter): LengthAwarePaginator
    {
        $micrositesQuery = Microsite::query()
            ->select('id', 'name', 'slug', 'category_id')
            ->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('category_id', $categoryFilter);
            })
            ->when($searchFilter, function ($query, $searchFilter) {
                return $query->where('name', 'like', '%' . $searchFilter . '%');
            });

        $microsites = $micrositesQuery->paginate(12)->withQueryString();

        $microsites->getCollection()->transform(function ($microsite) {
            return [
                'id' => $microsite->id,
                'name' => $microsite->name,
                'slug' => $microsite->slug,
                'logo' => asset('images/microsites/logo_'. random_int(1, 7) . '.png'),
            ];
        });

        return $microsites;
    }

    public function filterMricositesWithCategory(?string $categoryFilter, ?string $searchFilter)
    {
        $micrositesQuery = Microsite::query()
            ->select('microsites.id', 'microsites.name as microsite_name', 'microsites.slug', 'microsites.category_id', 'categories.name as category_name')
            ->leftJoin('categories', 'microsites.category_id', '=', 'categories.id')
            ->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('microsites.category_id', $categoryFilter);
            })
            ->when($searchFilter, function ($query, $searchFilter) {
                return $query->where('microsites.name', 'like', '%' . $searchFilter . '%');
            });

        $microsites = $micrositesQuery->paginate(10)->withQueryString();

        $microsites->getCollection()->transform(function ($microsite) {
            return [
                'id' => $microsite->id,
                'name' => $microsite->microsite_name,
                'slug' => $microsite->slug,
                'category' => [
                    'id' => $microsite->category_id,
                    'name' => $microsite->category_name,
                    'logo' => asset('images/categories/cat_' . random_int(1, 5) . '.svg'),
                ],
                'logo' => asset('images/microsites/logo_' . random_int(1, 7) . '.png'),
            ];
        });

        return $microsites;

    }
}
