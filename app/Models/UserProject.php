<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
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

    static function getProjectByUserId($userId) {
        return self::where('user_id', $userId)->first('project_id');
    }

    static function checkUserAccessForProject($projectId, $userId) {
        return self::where('project_id', $projectId)->where('user_id', $userId)->exists();
    }
}
