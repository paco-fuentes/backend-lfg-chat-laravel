<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
    //  * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
    //  * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function party_rooms(): HasMany
    {
        return $this->hasMany(PartyRoom::class);
    }
    public function party_members(): HasMany
    {
        return $this->hasMany(PartyMembers::class);
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Messages::class);
    }
    public function messageRoom(): BelongsToMany
    {
        return $this->belongsToMany(party_rooms::class, "messages");
    }
}
