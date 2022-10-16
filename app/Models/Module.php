<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'module_name',
        'module_type',
        'is_active'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function rolePermissions () {
        return $this->hasMany('App\Models\RolePermission');
    }
}
