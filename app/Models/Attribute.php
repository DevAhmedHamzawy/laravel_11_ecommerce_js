<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use Translatable, SoftDeletes;
    public $translatedAttributes = ['name'];
    protected $guarded = ['name'];

    public function children()
    {
        return $this->hasMany(Attribute::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Attribute::class, 'parent_id');
    }
}
