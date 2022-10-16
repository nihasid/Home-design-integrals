<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Model;

class ProjectStageComment extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'comment',
        'is_active'
    ];

    protected $appends = [
        'display_date'
    ];

    function getCreatedAtAttribute ( $value ) {
        return date(Constant::DATE_FORMAT, strtotime($value));
    }

    function getDisplayDateAttribute () {
        return date(Constant::DATE_DISPLAY, strtotime($this->created_at));
    }

    function user () {
        return $this->belongsTo('App\Models\User');
    }
}
