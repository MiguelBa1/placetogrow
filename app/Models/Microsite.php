<?php

namespace App\Models;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int id
 * @property string name
 * @property string slug
 * @property int category_id
 * @property CurrencyType payment_currency
 * @property int payment_expiration
 * @property MicrositeType type
 * @property string responsible_name
 * @property string responsible_document_number
 * @property string responsible_document_type
 * @property array settings
 * @property Category category
 * @property MicrositeField[] fields
 * @property Invoice[] invoices
 * @property Payment[] payments
 * @property Plan[] $plans
 */
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
        'settings',
    ];

    protected $casts = [
        'payment_currency' => CurrencyType::class,
        'type' => MicrositeType::class,
        'settings' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(MicrositeField::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class)->withTrashed();
    }
}
