<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string name
 * @property string label
 * @property string type
 * @property string validation_rules
 * @property array options
 * @property bool modifiable
 * @property Microsite microsite
 * @property FieldTranslation[] translations
 * @property Carbon created_at
 * @property Carbon updated_at
 */
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
        return $this->belongsTo(Microsite::class)->withTrashed();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FieldTranslation::class, 'field_id');
    }

}
