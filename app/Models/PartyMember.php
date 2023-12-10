<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartyMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'party_id',
        'is_active'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function party_rooms(): BelongsTo
    {
        return $this->belongsTo(PartyRoom::class, 'party_id');
    }
}
