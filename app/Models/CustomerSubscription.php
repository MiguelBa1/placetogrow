<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $subscription_id
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
 */
class CustomerSubscription extends Pivot
{
    public $incrementing = true;

    protected $table = 'customer_subscription';

    protected $fillable = [
        'customer_id',
        'subscription_id',
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
}
