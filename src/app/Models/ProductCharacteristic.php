<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCharacteristic extends Pivot
{
    protected $table = 'product_characteristics';
    use HasFactory;

    protected $fillable = ['product_id', 'characteristic_id', 'characteristic_value_id'];

    public function value()
    {
        return $this->belongsTo(CharacteristicValue::class, 'characteristic_value_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(Characteristic::class);
    }
}
