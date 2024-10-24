<?php

namespace App\Models;

use App\Constants\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int id
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
 * @property array additional_data
 * @property Customer customer
 * @property Microsite microsite
 * @property Invoice invoice
 * @method static PaymentFactory factory($count = null, $state = [])
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'microsite_id',
        'invoice_id',
        'plan_id',
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
        'additional_data',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'status' => PaymentStatus::class,
        'additional_data' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class)->withTrashed();
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }
}
