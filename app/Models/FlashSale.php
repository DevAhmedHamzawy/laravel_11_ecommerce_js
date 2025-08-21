<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(FlashSaleProduct::class);
    }
}
