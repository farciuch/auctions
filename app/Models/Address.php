<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['panstwo_id', 'adres', 'miasto', 'wojewodztwo', 'kod_pocztowy'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'panstwo_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'adres_id');
    }
}
