<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
 

    use HasFactory;

    protected $fillable = ['buyer_id', 'seller_id', 'item_id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($chat) {
            $chat->messages()->delete();
        });
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
