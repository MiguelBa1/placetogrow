<?php

namespace App\Models;

use App\Constants\BillingUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $microsite_id
 * @property string $name
 * @property string|null $description
 * @property int $price
 * @property int $total_duration
 * @property int $billing_frequency
 * @property BillingUnit $billing_unit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Microsite $microsite
 */
class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'microsite_id',
        'name',
        'description',
        'price',
        'total_duration',
        'billing_frequency',
        'billing_unit',
    ];

    protected $casts = [
        'billing_unit' => BillingUnit::class,
    ];

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SubscriptionTranslation::class);
    }
}
