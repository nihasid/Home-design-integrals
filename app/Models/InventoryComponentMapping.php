<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryComponentMapping extends Model {
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

    function item() {
        return $this->belongsTo('App\Models\InventoryItem');
    }

    function component() {
        return $this->belongsTo('App\Models\InventoryComponent');
    }

    function project() {
        return $this->belongsTo('App\Models\Project');
    }

    static function createInventory($request) {

        $inventory = new self;

        $inventory->fill([
            'project_id' => $request->project_id,
            'project_state' => serialize($request->project_state),
            'inventory_used' => serialize($request->inventory_used),
        ]);

        self::markOtherAsInActive($request);

        $inventory->save();
        // $data['item_id'] = $inventory->id;
        // $data['inventory_used'] = $inventory->inventory_used;
        // return $data;
        return $inventory->inventory_used;
    }

    static function markOtherAsInActive($request) {
        self::where('project_id', $request->project_id)
            ->update(['is_active' => 0]);
    }

    static function getinventoryByProjectId($request) {
        dd(self::with(['item', 'component'])->get()->toArray());
    }

}
