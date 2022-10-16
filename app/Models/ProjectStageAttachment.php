<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStageAttachment extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'attachment',
        'is_active',
        'name',
        'size',
    ];

    function user () {
        return $this->belongsTo('App\Models\User');
    }
}
