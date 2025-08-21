<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public $timestamps = false;

    public static function getMainAreas()
    {
        return self::whereParentId(1)->get();
    }

    public function cities()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }

    public function governorates()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }
}
