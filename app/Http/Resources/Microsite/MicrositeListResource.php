<?php

namespace App\Http\Resources\Microsite;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MicrositeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->only('id', 'name'),
            'type'=> __("microsite_types.{$this->type->value}"),
            'payment_currency' => __("currency_types.{$this->payment_currency->value}"),
            'payment_expiration' => __("payment_expirations.{$this->type->value}", ['days' => $this->payment_expiration]),
            'slug' => $this->slug,
            'responsible_name' => $this->responsible_name,
        ];
    }
}
