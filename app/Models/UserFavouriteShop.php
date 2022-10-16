<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavouriteShop extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'is_active',
    ];

    function user(){
        return $this->belongsTo('App\Models\User');
    }

    function shop(){
        return $this->belongsTo('App\Models\Shop');
    }
}
