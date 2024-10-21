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
            'type' => [
                'value' => $this->type->value,
                'label' => __("microsite_types.{$this->type->value}")
            ],
            'slug' => $this->slug,
            'responsible_name' => $this->responsible_name,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
