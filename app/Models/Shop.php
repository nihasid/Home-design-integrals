<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\UploadHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'country_id',
        'name',
        'is_active',
        'created_by'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    function getCreatedAtAttribute ( $value ) {
        return date(Constant::DATE_DISPLAY, strtotime($value));
    }

    function createdBy () {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    function favouriteShop () {
        return $this->hasOne('App\Models\UserFavouriteShop')->whereUserId(Auth::id());
    }

    function country () {
        return $this->belongsTo('App\Models\Country');
    }

    function projects(){
        return $this->hasMany('App\Models\Project');
    }

    function ongoingProject(){
        return $this->hasOne('App\Models\Project')->whereProjectStatus('Ongoing');
    }

    static function getAllShops( $request ) {

        $condition = [];

        if ($request->country_id) {
            $condition['country_id'] = $request->country_id;
        }

        $columns = [
            'id',
            'name',
            'country_id',
            'is_active'
        ];

        if ($request->dropdown) {
            $relations = [];
            $condition['is_active'] = true;
        } else {
            $relations = [
                'country' => function ($q) {
                    $q->select('id', 'name', 'region_id');
                },
                'country.region' => function ($q) {
                    $q->select('id', 'name');
                },
                'favouriteShop' => function ($q) {
                    $q->select('shop_id');
                },
                'ongoingProject' => function ($q) {
                    $q->select('shop_id');
                }
            ];
        }

        $query = Shop::select($columns)->with($relations);

        $query->where($condition);

        if ($request->region_id) {
            $country_ids = Country::select('id')->where('region_id', $request->region_id)->pluck('id');
            $query->whereIn('country_id', $country_ids);
        }

        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $user = Auth::user();

        if (Helper::isRegional($user) || Helper::isVendor($user)) {
            $query->wherehas('projects.userProjects', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('is_active', true);
            });
        }

        if ($request->is_favourite) {
            $favourite_shop_ids = UserFavouriteShop::select('shop_id')->where('user_id', Auth::id())->where('is_active', 1)->pluck('shop_id');
            $query->whereIn('id', $favourite_shop_ids);
        }

        if ($request->limit) {
            $query->offset($request->offset)->limit($request->limit);
        }

        if ($request->dropdown) {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('id', 'DESC');
        }

        $shops = $query->get();
        return $shops;
    }

    static function createShop ( $request ) {

        $shop = new self;

        $shop->fill([
            'country_id'    => $request->country_id,
            'name'          => $request->name,
            'is_active'     => $request->is_active,
            'created_by'    => Auth::id()
        ]);

        $shop->save();
        return $shop;
    }

    static function updateShop ( $request, $shop ) {

        $shop->fill([
            'country_id'    => $request->country_id,
            'name'          => $request->name,
            'is_active'     => $request->is_active,
        ]);

        $shop->save();
        return $shop;
    }

    static function addShopToFavourite($request)
    {
        $favShop = UserFavouriteShop::firstOrCreate([
            'user_id'       => Auth::id(),
            'shop_id'       => $request->shop_id
        ]);

        if ( !$request->is_favourite ) {
            $favShop->delete();
            return NULL;
        } else {
            return $favShop;
        }
    }

    static function shopRelations ($shopId = 0) {
        return [
            'createdBy' => function ($q) {
                $q->select('id')->where('is_active', true);
            },
            'createdBy.userDetails' => function ($q) {
                $q->select('user_id', 'full_name')->where('is_active', true);
            },
            'country' => function ($q) {
                $q->select('id', 'name', 'region_id')->where('is_active', true);
            },
            'country.region' => function ($q) {
                $q->select('id', 'name')->where('is_active', true);
            },
            'favouriteShop' => function ($q) {
                $q->select('shop_id')->where('is_active', true);
            },
            'projects'
        ];
    }

    static function shopExists ($request, $edit = false) {
        $shop = self::where([
            'country_id' => $request->country_id,
            'name' => $request->name,
        ]);

        if ($edit) {
            $shop->where([
                ['id', '!=', $request->shop->id]
            ]);
        }

        return $shop->exists();
    }

    static function getAllShopsDetail () {
        return self::select('id', 'name')->orderBy('name', 'ASC')->where('is_active', true)->get();
    }
}
