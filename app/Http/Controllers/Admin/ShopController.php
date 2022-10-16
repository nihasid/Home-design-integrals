<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ResponseHandler;
use App\Models\ExceptionLog;
use App\Models\Shop;
use App\Models\ShopProject;
use App\Models\UserProject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $shops = Shop::getAllShops($request);
            return ResponseHandler::success($shops);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        try {

            $validationErrors = Helper::validationErrors( $request, [
                'country_id'          => 'required|integer',
                'name'          => 'required|string',
            ]);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            if (Shop::shopExists($request)) {
                return ResponseHandler::validationError(['Shop of this name and country already exists.']);
            }

            $shop = Shop::createShop($request);
            return ResponseHandler::success($shop);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Shop $shop) {
        $user = Auth::user();


        if (Helper::isRegional($user) || Helper::isVendor($user)) {
            $shop = Shop::where('id', $shop->id);
            $shop = $shop->wherehas('projects.userProjects', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('is_active', true);
            })->first();
            if (!$shop) {
                return ResponseHandler::authorizationError();
            }
        }
        try {
            $shop->load(Shop::shopRelations());
            $is_assigned = 0;
            if ($shop['projects'] && count($shop['projects']) > 0) {
                $is_assigned = 1;
            }
            $shop['is_associated'] = $is_assigned;
            return ResponseHandler::success($shop);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Shop $shop) {
        try {

            if (Shop::shopExists($request, true)) {
                return ResponseHandler::validationError(['Shop of this name and country already exists.']);
            }

            $shop = Shop::updateShop($request, $shop);
            $shop->load(Shop::shopRelations());
            return ResponseHandler::success($shop);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop) {
        try {
            $shop = Shop::destroy($shop->id);
            return ResponseHandler::success($shop);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    public function addTofavourite(Request $request) {
        try {
            $shop = Shop::addShopToFavourite($request);
            return ResponseHandler::success($shop);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }
}
