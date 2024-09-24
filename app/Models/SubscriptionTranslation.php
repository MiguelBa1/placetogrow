<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $plan_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 * @property Plan $plan
 */
class SubscriptionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'locale',
        'name',
        'description',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
