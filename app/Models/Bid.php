<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;
    protected $fillable = ['bids_amount', 'bid_date', 'auction_id', 'bidder_id'];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function bidder()
    {
        return $this->belongsTo(User::class, 'bidder_id');
    }
}
