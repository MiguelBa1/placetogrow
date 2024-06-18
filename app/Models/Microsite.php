<?php

namespace App\Models;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Microsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'category_id',
        'payment_currency',
        'payment_expiration',
        'type'
    ];

    protected $casts = [
        'payment_currency' => CurrencyType::class,
        'type' => MicrositeType::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
