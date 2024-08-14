<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['field_id', 'locale', 'label'];

    public function field(): BelongsTo
    {
        return $this->belongsTo(MicrositeField::class);
    }
}
