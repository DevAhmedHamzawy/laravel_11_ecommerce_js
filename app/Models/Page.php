<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use Translatable, SoftDeletes;
    public $translatedAttributes = ['title', 'content'];
    protected $guarded = ['title', 'content'];
}
