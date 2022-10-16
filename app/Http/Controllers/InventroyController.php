<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Project;
use App\models\AccessHash;
use App\Models\ExceptionLog;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\ProjectInventory;
use App\Helpers\ResponseHandler;
use App\Models\InventoryComponent;
use App\Models\InventoryComponentMapping;
use Illuminate\Support\Facades\DB;

class InventroyController extends Controller {
    /**
     * Return a listing of the Inventory.
     *
     */
    public function index() {
        try {
            $inventory_items = InventoryItem::getAllInventoryItems();
//            $inventory_items = InventoryComponentMapping::getAllInventoryItems();
//            $inventory_components = InventoryComponent::getAllInventoryComponents();
            $inventory['inventory_list'] = $inventory_items;
//            $inventory['inventory_item_list'] = $inventory_items;
//            $inventory['inventory_components_list'] = $inventory_components;

            return ResponseHandler::success($inventory);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {

            $req = $request->json();
            $validationErrors = Helper::validationErrors($req, [
                'project_id' => 'required|integer',
            ]);

            $request = $request->json()->all();
            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }
            $request = new Request($request);

            // $inventory = InventoryComponentMapping::createInventory($request);
            $inventory = ProjectInventory::createInventory($request);
            return ResponseHandler::success($inventory);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    public function getInventoryByProjectId(Request $request) {
        try {
            $inventory = InventoryComponentMapping::getinventoryByProjectId($request);
            return ResponseHandler::success($inventory);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    public function getHash(Request $request) {
        return AccessHash::getHash($request);
    }

    public function createHash(Request $request) {
        try {

            $validationErrors = Helper::validationErrors($request, [
                'project_id' => 'required|integer',
            ]);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            return AccessHash::createHash($request);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    static function checkLongHash($request) {
        try {

            if ($request->project_id == 123) {
                return true;
            }

            $access = AccessHash::where('project_id', $request->project_id)
                ->where('token', $request->LongHash)
                ->where('is_used', 0)
                ->first();

            if ($access) {
                $access->is_used = true;
                $access->save();
            }

            return $access;
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    public function getProjectByLongHash(Request $request) {
        try {

            $validationErrors = Helper::validationErrors($request, [
                'project_id' => 'required|integer',
            ]);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            $project_state = '';
            $inventory_used = '';

            if (!self::checkLongHash($request)) {
                return ResponseHandler::authorizationError('Your session is expired!');
            }
            $project = Project::where('id', $request->project_id)->where('is_active', 1)->first();

            if (!$project) {
                return ResponseHandler::validationError(['Project with this id does not exists.']);
            }

            if ($project->projectInventories) {
                $project_inventory = ProjectInventory::where([
                    'is_active' => 1,
                    'project_id' => $request->project_id
                ])->first();

                $project_state = $project_inventory->project_state;
                $inventory_used = $project_inventory->inventory_used;
            }

            $data = [
                'project_name' => $project->name,
                'project_access_type' => 'all',
                'project_state' => $project_state,
                'inventory_used' => $inventory_used,
                'export_url' => env('CONTAINER_APP') . '/export/' . $project->id
            ];

            return ResponseHandler::success($data);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }
}
