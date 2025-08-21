<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseAttribute extends Model
{
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(Attribute::class, 'attribute_value_id');
    }
}
