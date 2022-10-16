<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function rolePermissions()
    {
        return $this->hasMany('App\Models\RolePermission');
    }

    static function getAllRoles()
    {
        $excludedIds = [1];
        return self::select('id', 'name')->where('is_active', true)->whereNotIn('id', $excludedIds)->get();
    }
}
