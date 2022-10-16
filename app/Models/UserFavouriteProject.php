<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavouriteProject extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'is_active',
    ];

    function user () {
        return $this->belongsTo('App\Models\User');
    }

    function project () {
        return $this->belongsTo('App\Models\Project');
    }
}
