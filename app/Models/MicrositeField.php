<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MicrositeField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'type',
        'validation_rules',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function microsites(): BelongsToMany
    {
        return $this->belongsToMany(Microsite::class, 'microsite_field_microsite')
            ->withPivot('modifiable')
            ->withTimestamps();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FieldTranslation::class, 'field_id');
    }

    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getTranslatedLabel($value),
        );
    }

    protected function getTranslatedLabel($value)
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation ? $translation->label : $value;
    }
}
