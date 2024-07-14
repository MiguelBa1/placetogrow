<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string reference
 * @property string request_id
 * @property string process_url
 * @property Carbon expires_in
 * @property string internal_reference
 * @property string franchise
 * @property string payment_method
 * @property string payment_method_name
 * @property string issuer_name
 * @property string receipt
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
        'request_id',
        'process_url',
        'expires_in',
        'internal_reference',
        'franchise',
        'payment_method',
        'payment_method_name',
        'issuer_name',
        'receipt',
        'authorization',
        'status',
        'status_message',
        'payment_date',
        'currency',
        'amount',
    ];

    protected $casts = [
        'expires_in' => 'datetime',
        'payment_date' => 'datetime',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
