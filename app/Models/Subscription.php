<?php

namespace App\Models;

use App\Constants\TimeUnit;
use Carbon\Carbon;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $plan_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $next_payment_date
 * @property string $status
 * @property string $reference
 * @property string $description
 * @property string $request_id
 * @property string $status_message
 * @property string $currency
 * @property string $token
 * @property string $subtoken
 * @property array $additional_data
 * @property float $price
 * @property TimeUnit $time_unit
 * @property int $billing_frequency
 * @property Plan $plan
 * @property Customer $customer
 * @property string $created_at
 * @property string $updated_at
 * @method static SubscriptionFactory factory($count = null, $state = [])
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
        'next_payment_date',
        'status',
        'reference',
        'description',
        'request_id',
        'status_message',
        'currency',
        'token',
        'subtoken',
        'additional_data',
        'price',
        'billing_frequency',
        'time_unit',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'time_unit' => TimeUnit::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'next_payment_date' => 'date',
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
