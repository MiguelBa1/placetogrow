<?php

namespace App\Models;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Microsite extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug, SoftDeletes;

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
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(MicrositeField::class, 'microsite_field_microsite')
            ->withPivot('is_required')
            ->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')
            ->useDisk('microsites_logos')
            ->singleFile();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
