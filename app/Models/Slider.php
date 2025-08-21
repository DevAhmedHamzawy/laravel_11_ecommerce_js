<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $guarded = [];

    public function getImgPathAttribute()
    {
        return url('storage/public/sliders/'.$this->image);
    }
}
