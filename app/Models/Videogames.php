<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Videogames extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'title',
        'year',
        'img_url',
        'genre',
        'is_active',
    ];

    public function party_rooms(): HasMany
    {
        return $this->hasMany(PartyRoom::class);
    }
}
