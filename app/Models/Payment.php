<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'payment_reference',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
