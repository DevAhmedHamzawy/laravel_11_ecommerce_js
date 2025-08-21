<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAttribute extends Model
{
    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(Attribute::class, 'attribute_value_id');
    }
}
