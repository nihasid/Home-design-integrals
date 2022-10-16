<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AccessToken extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'access_token',
        'expired_at',
        'is_active',
    ];

    function user ()
    {
        return $this->belongsTo('App\Models\v1\User', 'fk_user_id', 'users_id');
    }

    static function createToken( $userId )
    {
        $accessToken = new self;
        $accessToken->fill([
            'user_id'        => $userId,
            'access_token'   => $userId . '_' . Str::random(),
            'expired_at'     => date(Constant::DATE_TIME_FORMAT, strtotime('+ ' . Constant::TOKEN_EXPIRY_TIME)),
        ]);
        $accessToken->save();
        return $accessToken->access_token;
    }

}
