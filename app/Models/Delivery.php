<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'price'
      ];
      public function items()
      {
          return $this->belongsToMany(Item::class, 'delivery_item');
      }
      

}
