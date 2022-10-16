<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'full_name',
        'short_name',
        'company_name',
        'company_id',
        'position',
        'phone_number',
        'avatar',
        'is_active',
        'email_sent',
    ];

    function user () {
        return $this->belongsTo('App\Models\User');
    }
}
