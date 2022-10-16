<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapUserCompanies extends Model
{

    public $timestamps      = true;
    protected $table        = 'map_users_companies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'company_id',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id', 'id');
    }

}
