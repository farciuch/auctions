<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'is_ended'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($bid) {
            $bid->bids()->delete();
        });
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    public function updateCurrentPrice()
{
    $highestBid = $this->bids()->orderBy('bids_amount', 'desc')->first();
    if ($highestBid) {
        $this->item->update(['current_price' => $highestBid->bids_amount]);
    }

}

public function endAuction()
{
    $this->is_ended = true;
    $this->save();
}
}
