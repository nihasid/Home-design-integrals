<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectInventory extends Model {
    protected $fillable = [
        'project_id',
        'project_state',
        'inventory_used',
    ];

    function getInventoryUsedAttribute ($value) {
        return unserialize($value);
    }

    function getProjectStateAttribute ($value) {
        return unserialize($value);
    }

    static function createInventory($request) {

        $inventory = new self;

        $inventory->fill([
            'project_id' => $request->project_id,
            'project_state' => serialize($request->project_state),
            'inventory_used' => serialize($request->container['inventory_list']),
        ]);

        // Delete old record
         self::where('project_id', $request->project_id)->delete();
        // self::markOtherAsInActive($request);

        $inventory->save();
        return $inventory->inventory_used;
    }

    static function markOtherAsInActive($request) {
        self::where('project_id', $request->project_id)
            ->update(['is_active' => 0]);
    }
}
