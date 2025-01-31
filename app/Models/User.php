<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'adres_id', 'first_name', 'last_name', 'number', 'email', 'password'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'adres_id');
    }
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }
    public function cart()
    {
        return $this->hasMany(CartItem::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'bidder_id');
    }
    public function chatsAsBuyer()
    {
    return $this->hasMany(Chat::class, 'buyer_id');
    }

    public function chatsAsSeller()
    {
    return $this->hasMany(Chat::class, 'seller_id');
    }

    public function chats()
    {
        return $this->chatsAsBuyer->merge($this->chatsAsSeller);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
