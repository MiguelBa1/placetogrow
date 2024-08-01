<?php

namespace App\Models;

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
 * @property string status
 * @property string status_message
 * @property Carbon payment_date
 * @property string currency
 * @property string amount
 * @property Guest guest
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
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
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
