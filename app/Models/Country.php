<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'region_id',
        'name',
        'code',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function shops () {
        return $this->hasMany('App\Models\Shop');
    }

    function region () {
        return $this->belongsTo('App\Models\Region')->orderBy('name', 'asc');
    }

    static function getAllCountries() {
        return self::select( 'id', 'name')->orderBy('name', 'ASC')->where('is_active', true)->get();
    }
}
