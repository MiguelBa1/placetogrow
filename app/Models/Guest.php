<?php

namespace App\Models;

use App\Constants\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property string name
 * @property string last_name
 * @property DocumentType document_type
 * @property string document_number
 * @property string phone
 * @property string email
 * @property int user_id
 * @property User user
 * @property Collection payments
 */
class Guest extends Model
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'document_type',
        'document_number',
        'phone',
        'email',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
