<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ResponseHandler;
use App\Models\AccessHash;
use App\Models\AccessToken;
use App\Models\Country;
use App\Models\ExceptionLog;
use App\Models\Region;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    function login(Request $request)
    {

        try {

            $creds = $request->only('email', 'password');
            $creds['user_type'] = 'admin';

            $token = User::__authToken($creds);

            if ($token) {
                $cookie = cookie('token', $token, '', '', '', '', true, false, '');
                $response = ResponseHandler::success([], 'Login Successful', $cookie);
            } else {
                $response = ResponseHandler::validationError(['Invalid Email or Password']);
            }

            return $response;


        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }

    }

    function authUser(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = $request->user();
                $user->load([
                    'userDetails' => function ($q) {
                        $q->select([
                            'user_id',
                            'full_name',
                            'short_name',
                            'company_id',
                            'company_name',
                            'position',
                            'avatar'
                        ])->where('is_active', true);
                    },
                    'userRole' => function ($q) {
                        $q->select('user_id', 'role_id')->where('is_active', true);
                    },
                    'userRole.role' => function ($q) {
                        $q->select('id', 'name')->where('is_active', true);
                    } ,
                ]);
                $user->modules = User::loggedInUserModules( $user );
                $user->countries = Country::getAllCountries();
                $user->regions = Region::getAllRegions();
                $user->vendors = Vendor::getAllVendors();
                $user->shops = Shop::getAllShopsDetail();
                $user->roles = Role::getAllRoles();

                $response = ResponseHandler::success($user);
            } else {
                $response = ResponseHandler::authenticationError();
            }

            return $response;

        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }
    }

    function logout(Request $request)
    {
        try {
            AccessToken::where('access_token', $request->token)->delete();
            return ResponseHandler::success([]);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }
    }

    function forgot_password(Request $request)
    {
        try {
            $this->validate($request, ['email' => 'required|email']);
            if ($request->wantsJson()) {
                $user = User::where('email', $request->input('email'))->first();
                if (!$user) {
                    return ResponseHandler::authenticationError(['message' => 'User with this email does not exists!']);
                }
                $token = app('auth.password.broker')->createToken($user);
                return ResponseHandler::success(['token' => $token]);
            }
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }
    }

    public function createHash(Request $request)
    {
        try {

            $validationErrors = Helper::validationErrors( $request, [
                'project_id'          => 'required|integer',
            ]);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            $accessHash = AccessHash::createHash($request);
            $accessHash['design_tool_url'] = env('DESIGN_TOOL_URL');
            return ResponseHandler::success( $accessHash );
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }
}
