<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\UploadHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'short_name',
        'domain',
        'created_by',
        'is_active',
        'logo'
    ];

    protected $hidden = [
        'updated_at',
    ];

    function getCreatedAtAttribute ( $value ) {
        return date(Constant::DATE_DISPLAY, strtotime($value));
    }

    function createdBy () {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    function users () {
        return $this->hasMany('App\Models\User');
    }

    static function getVendorsList( $request ) {

        $query = self::select('id','name','domain','logo','is_active','created_by');

        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->limit) {
            $query->offset($request->offset)->limit($request->limit);
        }

        $query->orderBy('id', 'DESC');
        $vendors = $query->get();
        return $vendors;
    }

    static function createVendor ( $request ) {

        if ($request->logo) {
            $request->logo = UploadHelper::UploadFile($request->logo, 'vendor-logo');
        }

        $vendor = new self;

        $vendor->fill([
            'name'          => $request->name,
            'short_name'    => self::getVendorShortname ($request->name),
            'domain'        => $request->domain,
            'logo'          => $request->logo ? $request->logo : null,
            'is_active'     => Constant::BOOL_STR[$request->is_active],
            'created_by'    => Auth::id(),
        ]);

        $vendor->save();
        return $vendor;
    }

    static function updateVendor ( $request, $vendor ) {
        $logo = '';
        if ($request->logo) {
            UploadHelper::deleteFile($vendor->logo);
            $logo = UploadHelper::UploadFile($request->logo, 'vendor-logo');
        }

        $vendorDetails = [
            'name'          => $request->name,
            'short_name'    => self::getVendorShortname ($request->name),
            'domain'        => $request->domain,
            'is_active'     => Constant::BOOL_STR[$request->is_active],
        ];

        if ( $logo ) {
            $vendorDetails['logo'] = $logo;
        }

        $vendor->fill($vendorDetails)->save();

        return $vendor;
    }

    static function getVendorShortname ($name) {
        $vendorShortName = explode(' ', str_replace('-' , ' ', $name));
        if(isset($vendorShortName[1])) {
            $sn = $vendorShortName[0][0] . $vendorShortName[1][0];
        } else {
            $sn = $vendorShortName[0][0] . $vendorShortName[0][1];
        }
        return strtoupper($sn);
    }


    static function getAllVendors () {
        return self::select('id', 'name')->orderBy('name', 'ASC')->where('is_active', true)->get();
    }

    static function vendorExists ($request, $edit = false) {
        $vendor = self::where([
            'name' => $request->name,
            'domain' => $request->domain
        ]);

        if ($edit) {
            $vendor->where([
                ['id', '!=', $request->vendor->id]
            ]);
        }

        return $vendor->exists();
    }
}
