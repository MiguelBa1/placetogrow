<?php

namespace App\Http\Resources\Microsite;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MicrositeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category ? $this->category->only('id', 'name') : null,
            'type' => __("microsite_types.{$this->type->value}"),
            'payment_currency' => __("currency_types.{$this->payment_currency->value}"),
            'payment_expiration' => __("payment_expirations.{$this->type->value}", ['days' => $this->payment_expiration]),
            'responsible_name' => $this->responsible_name,
            'responsible_document_number' => $this->responsible_document_number,
            'responsible_document_type' => __("document_types.{$this->responsible_document_type}"),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'logo' => $this->getFirstMediaUrl('logos'),
        ];
    }
}
