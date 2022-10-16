<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\UploadHelper;
use App\Models\MapUserCompanies;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;
    protected $table = "users";
    protected $fillable = [
        'email',
        'password',
        'user_type',
        'is_active',
        'created_by',
        'vendor_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'deleted_at',
    ];

    function getCreatedAtAttribute ( $value ) {
        return date(Constant::DATE_DISPLAY, strtotime($value));
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function accessTokens()
    {
        return $this->hasMany('App\Models\AccessToken');
    }

    function createdBy () {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    function userRole()
    {
        return $this->hasOne('App\Models\UserRole');
    }


    function userDetails()
    {
        return $this->hasOne('App\Models\UserDetail');
    }

    function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    function userProjects() {
        return $this->hasMany('App\Models\UserProject');
    }

    static function __authToken($creds)
    {
        return Auth::attempt($creds) ? AccessToken::createToken(Auth::id()) : false;
    }

    static function loggedInUserModules($user)
    {
        $query = Module::select([
            'id',
            'module_name',
            'route',
            'icon_class',
            'active_class'
        ]);
        if ($user->userRole->role_id != Constant::USER_ROLES['superAdmin']) {
            $query->whereHas('rolePermissions', function ($q) use ($user) {
                $roleConditions = [
                    'permission_level' => 'read',
                    'role_id' => $user->userRole->role_id,
                    'is_active' => true
                ];
                $q->where($roleConditions);
            });
        }

        $conditions = [
            'module_type'   => 'admin',
            'is_active'     => true,
        ];

        $modules = $query->where($conditions)->orderBy('display_order', 'ASC')->get();

        return $modules;
    }

    static function userList($request)
    {
        $conditions = [];
        $searchValue = $request->search;
        $project_memberIds = $request->add_project_memberIds;

        //companies.company
        $query = self::with('userDetails', 'userRole.role');

        if ($request->vendor_id) {
            $conditions['vendor_id'] = (int)$request->vendor_id;
            $query->where($conditions);
        }

        if ($request->region_id) {
            $query->whereHas('userDetails', function ($q) use ($request) {
                $q->where('company_id', $request->region_id);
            });
        }

        $user = Auth::user();

        if (Helper::isVendor($user)) {
            $query->where('vendor_id', $user->vendor_id);
        } elseif (Helper::isRegional($user)) {
            $query->whereHas('userRole', function ($q) {
                $q->whereIn('role_id', [
                    Constant::USER_ROLES['admin'],
                    Constant::USER_ROLES['samsungAdmin'],
                    Constant::USER_ROLES['cheilAdmin'],
                    Constant::USER_ROLES['samsungRegional'],
                    Constant::USER_ROLES['cheilRegional'],
                ]);
            });

            $query->whereHas('userDetails', function ($q) use($user) {
                $q->where('company_id', $user->userDetails->company_id)->orWhereNull('company_id');
            });
        }

        if (isset($searchValue)) {
            $query->whereHas('userDetails', function ($q) use ($searchValue, $project_memberIds) {
                $q->where('email', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $searchValue . "%");
            });
        }

        if ($request->add_project_member) {
            $query->where('is_active', true);
        }

        // exclude super admin users
        $excludedIds = [1];

        if($project_memberIds) {
            $excludedIds = array_merge($excludedIds, $project_memberIds);
        }

        $query->whereNotIn('id', $excludedIds);

        if ($request->limit) {
            $query->offset($request->offset)->limit($request->limit);
        }

        return $query->orderBy('id', 'DESC')->get()->toArray();
    }

    static function create($data)
    {
        $image = '';
        if (isset($data['avatar']) && $data['avatar']) {
            $image = UploadHelper::UploadFile($data['avatar'], 'profile-pictures');
        }

        $user = new self();

        $user->fill([
            'email'         => $data['email'],
            'is_active'     => Constant::BOOL_STR[$data['status']],
            'vendor_id'     => $data['roleId'] == Constant::USER_ROLES['vendor'] ? $data['companyId'] : 0,
            'password'      => bcrypt($data['password']),
            'created_by'    => Auth::id(),
        ]);

        $userDetails = new UserDetail([
            'first_name'            => $data['firstName'],
            'last_name'             => $data['lastName'],
            'full_name'             => $data['firstName'].' '.$data['lastName'],
            'short_name'            => strtoupper($data['firstName'][0] . $data['lastName'][0]),
            'company_name'          => $data['companyName'],
            'company_id'            => $data['companyId'],
            'position'              => $data['position'],
            'email_notification'    => (isset($data['email_sent']) ? 1 : 0),
            'avatar'                => $image
        ]);

        $userRole = new UserRole([
            'role_id' => $data['roleId']
        ]);

        $user->save();
        $user->userDetails()->save($userDetails);
        $user->userRole()->save($userRole);

        $user->load(['userDetails', 'userRole.role:id,name']);
        return $user;
    }

    static function show($user)
    {
        return self::with([
            'userDetails',
            'createdBy.userDetails',
            'userRole.role' => function ($q) {
                $q->select('id', 'name');
        }])->where('id', $user['id'])->first();
    }

    static function updateUser($data, $user)
    {
        $image = '';
        if (isset($data['avatar']) && $data['avatar']) {
            UploadHelper::deleteFile($user->avatar);
            $image = UploadHelper::UploadFile($data['avatar'], 'profile-pictures');
        }

        $user->fill([
            'email'         => $data['email'],
            'is_active'     => Constant::BOOL_STR[$data['status']],
            'vendor_id'     => $data['roleId'] == Constant::USER_ROLES['vendor'] ? $data['companyId'] : 0,
            'created_by'    => Auth::id(),
        ]);

        $userDetails = UserDetail::where('user_id', $user->id)->first();

        $userDetailsData = [
            'first_name'            => $data['firstName'],
            'last_name'             => $data['lastName'],
            'full_name'             => $data['firstName'] . ' ' . $data['lastName'],
            'short_name'            => strtoupper($data['firstName'][0] . $data['lastName'][0]),
            'company_name'          => $data['companyName'],
            'company_id'            => $data['companyId'],
            'position'              => $data['position'],
            'email_notification'    => (isset($data['emailNotification']) ? 1 : 0),
        ];

        if ($image) {
            $userDetailsData['avatar'] = $image;
        }

        $userDetails->fill($userDetailsData);

        $userRole = UserRole::where('user_id', $user->id)->first();
        $userRole->fill([
            'role_id' => $data['roleId']
        ]);

        $user->save();
        $userDetails->save();
        $userRole->save();

        $user->load(['userDetails', 'createdBy.userDetails', 'userRole.role' => function ($q) {
            $q->select('id', 'name');
        }]);
        return $user;
    }

    static function deleteUsers($idsArray = array())
    {
        $response = self::whereIn('id', $idsArray)->delete();
        if ($response) {
            $userDetails = new UserDetail();
            $userDetails->whereIn('user_id', $idsArray)->delete();
        }
        return $response;

    }
}
