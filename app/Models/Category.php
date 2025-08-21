<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, Translatable, SoftDeletes;
    public $translatedAttributes = ['name', 'description'];

    protected $guarded = ['name', 'description', 'main_image'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function getImgPathAttribute()
    {
        return url('storage/public/categories/'.$this->image);
    }
}
