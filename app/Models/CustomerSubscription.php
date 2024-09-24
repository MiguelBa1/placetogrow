<?php

namespace App\Models;

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
 * @property Plan $plan
 * @property Customer $customer
 * @property string $created_at
 * @property string $updated_at
 */
class CustomerSubscription extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'subscription';

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
    ];

    protected $casts = [
        'additional_data' => 'array',
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
