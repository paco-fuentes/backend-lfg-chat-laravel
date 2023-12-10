<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartyRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'admin_id',
        'img_url',
        'visibility',
        'videogame_id',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videogames(): BelongsTo
    {
        return $this->belongsTo(Videogames::class);
    }

    public function party_members(): HasMany
    {
        return $this->hasMany(PartyMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'party_members', 'party_id', 'user_id')->withTimestamps();
    }
}
