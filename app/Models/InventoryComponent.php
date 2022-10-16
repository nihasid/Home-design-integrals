<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryComponent extends Model {

    public $incrementing = false;
    protected $keyType = 'string';

    static function getAllInventoryComponents() {
        return self::select('id as item_id', 'display_name as item_name', 'asset_path as item_asset_url', 'icon_path as item_icon_path')
            ->where('is_active', 1)
            ->get();
    }
}
