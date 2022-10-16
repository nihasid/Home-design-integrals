<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function countries()
    {
        return $this->hasMany('App\Models\Country');
    }

    static function getAllRegions()
    {
        return self::select('id', 'name')->orderBy('name', 'ASC')->where('is_active', true)->get();
    }

    static function getRegionsById($id)
    {
        return self::select('id', 'name')->get()->toArray();
    }

}
