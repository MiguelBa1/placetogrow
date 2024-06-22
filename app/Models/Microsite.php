<?php

namespace App\Models;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Microsite extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'category_id',
        'payment_currency',
        'payment_expiration',
        'type',
        'responsible_name',
        'responsible_document_number',
        'responsible_document_type',
    ];

    protected $casts = [
        'payment_currency' => CurrencyType::class,
        'type' => MicrositeType::class,
        'payment_expiration' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')
            ->useDisk('microsites')
            ->singleFile();
    }
}
