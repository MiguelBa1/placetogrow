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
                    'logo' => $category->getFirstMediaUrl('logos')
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

        $microsites = $micrositesQuery->paginate(10)->withQueryString();

        $microsites->getCollection()->transform(function ($microsite) {
            return [
                'id' => $microsite->id,
                'name' => $microsite->name,
                'slug' => $microsite->slug,
                'logo' => $microsite->getFirstMediaUrl('logos')
            ];
        });

        return $microsites;
    }
}
