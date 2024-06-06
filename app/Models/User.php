<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'document_type',
        'document_number',
        'phone',
        'email',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
