<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    protected $fillable = ['name_uz', 'name_ru', 'order'];
    use HasFactory;

    public function values()
    {
        return $this->hasMany(CharacteristicValue::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_characteristics')->withTimestamps();
    }
}
