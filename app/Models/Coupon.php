<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];


    public function isValid($orderTotal)
    {
        return $this->active
            && now()->between($this->start_date, $this->end_date)
            && ($this->max_usage === null || $this->used_count < $this->max_usage)
            && $orderTotal >= $this->min_order;
    }
}
