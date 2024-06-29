<?php

namespace App\Services;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Http\Resources\MicrositeResource;
use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MicrositeService
{
    public function getAllMicrosites(): AnonymousResourceCollection
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
            )->paginate(10)->withQueryString()->onEachSide(1);

        return MicrositeResource::collection($microsites);
    }

    public function getMicrositeData(Microsite $microsite): array
    {
        $micrositeData = $microsite->only(['id', 'name', 'slug', 'payment_currency']);
        $micrositeData['logo'] = $microsite->getFirstMediaUrl('logos');

        return $micrositeData;
    }

    public function getFormData(): array
    {
        return [
            'categories' => Category::query()->select('id', 'name')->get(),
            'documentTypes' => DocumentType::toSelectArray(),
            'micrositeTypes' => MicrositeType::toSelectArray(),
            'currencyTypes' => CurrencyType::toSelectArray(),
        ];
    }

    public function getEditData(Microsite $microsite): array
    {
        $microsite->load('category:id,name');
        $micrositeData = $microsite->only(['id', 'name', 'slug', 'category_id', 'type', 'payment_currency', 'payment_expiration', 'responsible_name', 'responsible_document_number', 'responsible_document_type']);
        $micrositeData['logo'] = $microsite->getFirstMediaUrl('logos');

        return $micrositeData;
    }
}
