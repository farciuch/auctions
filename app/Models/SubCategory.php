<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['main_category_id', 'name'];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }


    public function subSubCategories()
    {
        return $this->hasMany(SubSubCategories::class, 'sub_category_id');
    }
    public function items()
    {
        return $this->hasManyThrough(Item::class, SubSubCategories::class);
    }
}
