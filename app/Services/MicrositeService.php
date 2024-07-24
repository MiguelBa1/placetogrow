<?php

namespace App\Services;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Http\Resources\Microsite\MicrositeDetailResource;
use App\Http\Resources\Microsite\MicrositeListResource;
use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class MicrositeService
{
    public function getAllMicrosites(?string $searchFilter, ?string $categoryFilter): AnonymousResourceCollection
    {
        $microsites = Microsite::withTrashed()->with('category:id,name')
            ->select(
                'id',
                'name',
                'category_id',
                'type',
                'slug',
                'responsible_name',
                'payment_currency',
                'payment_expiration',
                'deleted_at',
            )->when($searchFilter, function ($query, $searchFilter) {
                return $query->where('name', 'like', '%' . $searchFilter . '%');
            })->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('category_id', $categoryFilter);
            })->paginate(10)->onEachSide(1)->withQueryString();

        return MicrositeListResource::collection($microsites);
    }

    public function getMicrositeData(Microsite $microsite): array
    {
        $microsite->load('category:id,name');
        return (new MicrositeDetailResource($microsite))->toArray(request());
    }

    public function getFormData(): array
    {
        $categories = Cache::remember('categories', 86400, function () {
            return Category::select('id', 'name')->get();
        });

        return [
            'categories' => $categories,
            'documentTypes' => DocumentType::toSelectArray(),
            'micrositeTypes' => MicrositeType::toSelectArray(),
            'currencyTypes' => CurrencyType::toSelectArray(),
        ];
    }

    public function getEditData(Microsite $microsite): array
    {
        $micrositeData = $microsite->only([
            'id',
            'name',
            'slug',
            'category_id',
            'type',
            'payment_currency',
            'payment_expiration',
            'responsible_name',
            'responsible_document_number',
            'responsible_document_type',
        ]);
        $micrositeData['logo'] = $microsite->getFirstMediaUrl('logos');

        return $micrositeData;
    }
}
