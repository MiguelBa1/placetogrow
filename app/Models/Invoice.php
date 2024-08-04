<?php

namespace App\Models;

use App\Constants\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'microsite_id',
        'reference',
        'document_type',
        'document_number',
        'name',
        'status',
        'last_name',
        'email',
        'phone',
        'amount',
        'expiration_date',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
    ];

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }
}
