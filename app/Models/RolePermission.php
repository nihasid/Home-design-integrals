<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role_id',
        'module_id',
        'permission_level',
        'is_active'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function module () {
        return $this->belongsTo('App\Models\Module');
    }

    function role () {
        return $this->belongsTo('App\Models\Role');
    }
}
