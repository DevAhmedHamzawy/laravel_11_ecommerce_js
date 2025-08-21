<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockable()
    {
        return $this->morphTo();
    }

    public function attributes()
    {
        return $this->hasMany(StockAttribute::class);
    }
}
