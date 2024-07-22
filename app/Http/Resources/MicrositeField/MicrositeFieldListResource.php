<?php

namespace App\Http\Resources\MicrositeField;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MicrositeFieldListResource extends JsonResource
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
            'label' => __("microsite_fields.{$this->name}"),
            'type' => $this->type,
            'is_required' => $this->pivot->is_required,
            'validation_rules' => $this->validation_rules,
        ];
    }
}
