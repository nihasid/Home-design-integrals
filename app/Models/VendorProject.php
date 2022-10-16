<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProject extends Model
{
    protected $fillable = [
        'vendor_id',
        'project_id',
        'is_active',
    ];

    function vendor () {
        return $this->belongsTo('App\Models\Vendor');
    }

    function project () {
        return $this->belongsTo('App\Models\Project');
    }
}
