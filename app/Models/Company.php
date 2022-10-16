<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "companies";
    protected $fillable = [
        'company_name',
        'website',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function accessTokens () {
        return $this->hasMany('App\Models\AccessToken');
    }

    function companies () {
        return $this->hasMany('App\Models\User');
    }

    static function getAllCompanies() {
        return self::select('id','company_name','website')->get()->toArray();
    }
    
}
