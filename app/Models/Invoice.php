<?php

namespace App\Models;

use App\Constants\InvoiceStatus;
use Carbon\Carbon;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string reference
 * @property string document_type
 * @property string document_number
 * @property string name
 * @property string last_name
 * @property string email
 * @property string phone
 * @property float amount
 * @property Carbon expiration_date
 * @property InvoiceStatus status
 * @property Microsite microsite
 * @property Payment payment
 * @property int microsite_id
 * @property int id
 * @property float late_fee
 * @property float total_amount
 * @method static InvoiceFactory factory($count = null, $state = [])
 */
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
        'late_fee',
        'total_amount',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'expiration_date' => 'date',
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
