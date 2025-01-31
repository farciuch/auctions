<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubCategories extends Model
{
    use HasFactory;
    protected $fillable = ['sub_category_id', 'name'];
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
}
