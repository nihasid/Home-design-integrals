<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProject extends Model
{
    protected $fillable = [
        'shop_id',
        'project_id',
        'is_active',
    ];

    function shop () {
        return $this->belongsTo('App\Models\Shop');
    }

    function project () {
        return $this->belongsTo('App\Models\Project');
    }
}
