<?php

namespace App\Models;

use App\Constants\TimeUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $plan_id
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property string $reference
 * @property string $description
 * @property string $request_id
 * @property string $status_message
 * @property string $currency
 * @property string $token
 * @property string $subtoken
 * @property array $additional_data
 * @property float $initial_price
 * @property int $initial_duration
 * @property TimeUnit $initial_time_unit
 * @property Plan $plan
 * @property Customer $customer
 * @property string $created_at
 * @property string $updated_at
 */
class Subscription extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'subscriptions';

    protected $fillable = [
        'customer_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'reference',
        'description',
        'request_id',
        'status_message',
        'currency',
        'token',
        'subtoken',
        'additional_data',
        'initial_price',
        'initial_duration',
        'initial_time_unit',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'initial_time_unit' => TimeUnit::class,
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
