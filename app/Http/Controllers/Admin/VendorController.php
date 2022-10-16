<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ResponseHandler;
use App\Models\ExceptionLog;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    private static $validationRules = [
        'create' => [
            'name'      => 'required|string|min:2',
            'domain'    => 'required|string'
        ],
        'update' => [
            'name'      => 'required|string|min:2',
            'domain'    => 'required|string'
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $vendors = Vendor::getVendorsList($request);
            return ResponseHandler::success( $vendors );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validationErrors = Helper::validationErrors($request, self::$validationRules['create']);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            if(Vendor::vendorExists($request)){
                return ResponseHandler::validationError(['Vendor of this name and domain already exists.']);
            }

            $vendor = Vendor::createVendor( $request );
            return ResponseHandler::success( $vendor );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Vendor $vendor)
    {
        try {
            $vendor->load('createdBy.userDetails');
            return ResponseHandler::success( $vendor );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Vendor $vendor)
    {
        try {
            $validationErrors = Helper::validationErrors($request, self::$validationRules['update']);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            if(Vendor::vendorExists($request, true)){
                return ResponseHandler::validationError(['Vendor of this name and domain already exists.']);
            }

            $vendor = Vendor::updateVendor( $request, $vendor );
            $vendor->load('createdBy.userDetails');
            return ResponseHandler::success( $vendor );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Vendor $vendor)
    {
        try {
            $vendor = Vendor::destroy( $vendor->id );
            return ResponseHandler::success( $vendor );
        } catch (\Exception $e) {
            ExceptionLog::log( $e, self::$routeAction );
            return ResponseHandler::serverError( $e );
        }
    }
}
