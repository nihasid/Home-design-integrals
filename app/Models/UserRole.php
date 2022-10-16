<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userRole extends Model
{
    protected $fillable = [
        'user_id',
        'role_id',
        'is_active'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function role () {
        return $this->belongsTo('App\Models\Role')->orderBy('name', 'asc');
    }
}
