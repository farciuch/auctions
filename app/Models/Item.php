<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'category_id','condition_id', 'item_type_id', 'title', 'description',
        'starting_price', 'current_price', 'start_time', 'end_time', 'status', 'price', 'delivery_id', 'quantity'
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            // Usuwanie powiązanych rekordów z tabeli fixed_price_items
            $item->fixedPrice()->delete();
            // Usuwanie powiązanych rekordów z innych tabel, jeśli istnieją
            $item->auction()->delete();
            $item->images()->delete();
            $item->favorites()->delete();
            $item->cart()->delete();
            // Usuwanie powiązanych rekordów z tabeli chats (która z kolei usunie messages)
            $item->chat()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(SubSubCategories::class, 'category_id');
    }

    public function condition()
    {
        return $this->belongsTo(Conditions::class, 'condition_id');
    }

    public function deliveries()
    {
    return $this->belongsToMany(Delivery::class, 'delivery_item');
    }

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }
    public function chat()
    {
        return $this->hasMany(Chat::class);
    }

    public function fixedPrice()
    {
        return $this->hasOne(FixedPriceItem::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }
    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }
    public function cart()
    {
        return $this->hasMany(CartItem::class);
    }
    public function isAuctionActive()
    {
        $now = Carbon::now();
        return Carbon::parse($this->start_time)->lessThanOrEqualTo($now) && Carbon::parse($this->end_time)->greaterThanOrEqualTo($now);
    }
    public function isAuctionEnded()
    {
        $now = Carbon::now();
        return $this->auction && (Carbon::parse($this->end_time)->lessThan($now) || $this->auction->is_ended);
    }
    public function currentPrice()
    {
        // Zakładam, że 'current_price' jest atrybutem, który jest aktualizowany podczas licytacji
        return $this->current_price ?? $this->starting_price;
    }

}
