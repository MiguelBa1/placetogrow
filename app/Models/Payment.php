<?php

namespace App\Models;

use App\Constants\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string reference
 * @property string request_id
 * @property string description
 * @property string payment_method_name
 * @property string authorization
 * @property PaymentStatus status
 * @property string status_message
 * @property Carbon payment_date
 * @property string currency
 * @property string amount
 * @property Customer customer
 * @property Microsite microsite
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'microsite_id',
        'reference',
        'description',
        'request_id',
        'payment_method_name',
        'authorization',
        'status',
        'status_message',
        'payment_date',
        'currency',
        'amount',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'status' => PaymentStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }
}
