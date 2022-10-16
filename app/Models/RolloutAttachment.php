<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolloutAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'rollout_id',
        'attachment',
        'name',
        'size',
        'is_active',
    ];
}
