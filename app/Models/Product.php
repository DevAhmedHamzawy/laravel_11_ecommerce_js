<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Translatable, SoftDeletes;
    public $translatedAttributes = ['name', 'mini_description', 'description'];

    protected $guarded = ['name', 'mini_description', 'description' , 'main_image', 'images'];

    public function getRouteKeyName()
    {
        if (request()->is('admin/*')) {
            return 'id';
        }

        return 'slug';
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function getImgPathAttribute()
    {
        return url('storage/public/products/'.$this->image);
    }
}
