<?php

namespace App\Models;

use App\Constants\TimeUnit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $microsite_id
 * @property string|null $description
 * @property int $price
 * @property int $total_duration
 * @property int $billing_frequency
 * @property TimeUnit $time_unit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Microsite $microsite
 * @property Collection|SubscriptionTranslation[] $translations
 */
class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'microsite_id',
        'description',
        'price',
        'total_duration',
        'billing_frequency',
        'time_unit',
    ];

    protected $casts = [
        'time_unit' => TimeUnit::class,
    ];

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SubscriptionTranslation::class);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_subscription')
            ->using(CustomerSubscription::class)
            ->withTimestamps();
    }
}
