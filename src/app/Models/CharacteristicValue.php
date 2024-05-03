<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacteristicValue extends Model
{
    use HasFactory;

    protected $fillable = ['characteristic_id', 'value_uz', 'value_ru'];

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class);
    }
}
