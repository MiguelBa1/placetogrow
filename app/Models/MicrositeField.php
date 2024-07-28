<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'modifiable',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FieldTranslation::class, 'field_id');
    }

}
