<?php

namespace App\Http\Resources\MicrositeField;

use App\Models\MicrositeField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/* @mixin MicrositeField */
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
        $locale = app()->getLocale();

        $translation = $this->translations->firstWhere('locale', $locale);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $translation->label ?? '',
            'type' => $this->type,
            'modifiable' => (bool) $this->modifiable,
            'validation_rules' => $this->validation_rules,
            'translation_en' => $this->translations->where('locale', 'en')->first()->label ?? '',
            'translation_es' => $this->translations->where('locale', 'es')->first()->label ?? '',
            'options' => $this->options ? implode(', ', $this->options) : null,
        ];
    }
}
