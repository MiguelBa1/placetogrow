<?php


namespace App\Http\Resources\MicrositeField;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MicrositeFieldDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'options' => $this->options ? array_map(function ($option) {
                return [
                    'label' => $option,
                    'value' => $option,
                ];
            }, $this->options) : []
        ];
    }
}
