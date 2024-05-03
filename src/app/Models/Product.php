<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'array'
    ];

    protected $fillable = [
        'name_uz', 'name_ru', 'description_uz', 'description_ru', 'price',
        'discount_price', 'discount_percent', 'in_stock', 'artikul', 'category_id', 'brand_id', 'images'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, 'product_characteristics')->withPivot(['characteristic_value_id'])->withTimestamps();

//          return $this->hasMany(ProductCharacteristic::class, 'characteristic_id', 'product_id');
    }


}
