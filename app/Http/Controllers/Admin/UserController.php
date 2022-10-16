<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ResponseHandler;
use App\Models\AccessToken;
use App\Models\ExceptionLog;
use App\Models\Region;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\UserDetail;
use App\Models\UserProject;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class   UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $users = User::userList($request);
            return ResponseHandler::success( $users );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    /* ### User Create / Update Method ### */
    public function store(Request $request)
    {
        try {
            if (User::where('email', '=', $request->email)->exists()) {
                return ResponseHandler::validationError(['User with this email already exists.']);
            }

            $input = $request->all();
            $response = User::create($input);
            return ResponseHandler::success($response, 'created successfully');
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        try {

            $user->load([
                'userDetails',
                'createdBy.userDetails',
                'userRole.role'
            ]);

            return ResponseHandler::success( $user );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            if (User::where('email', '=', $request->email)->where('id', '!=', $user->id)->exists()) {
                return ResponseHandler::validationError(['User with this email address already exists.']);
            }

            $input = $request->all();
            $response = User::updateUser($input, $user);
            return ResponseHandler::success($response, 'updated successfully');
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        try {
            $user = User::destroy( $input['idsArray'] );
            return ResponseHandler::success( $user );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    public function getCompaniesById(Request $request, $id)
    {
        try {
            $data = Region::getRegionsById($id);
            return ResponseHandler::success($data);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError( $e );
        }

    }
}
