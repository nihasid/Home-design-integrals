<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {
    public function itemComponents() {
        return $this->hasMany(InventoryComponentMapping::class, 'item_id');
    }

//    function components() {
//        return $this->hasMany('App\Models\InventoryComponentMapping', 'item_id');
//    }

    static function getAllInventoryItems() {
        $inventory_items = self::select('id as item_id', 'display_name as item_name', 'group', 'category', 'type', 'asset_path as item_asset_url', 'icon_path as item_icon_path')
            ->where('is_active', 1)
            ->get();
        $inventory_list = [];
        foreach ($inventory_items as $inventory_item) {
            $item_components = InventoryComponentMapping::where('item_id', $inventory_item->item_id)
                ->where('is_active', 1)
                ->get();

            $data_components = [];
            foreach ($item_components as $item_component) {
                $quantity = isset($item_component) ? $item_component->quantity : 0;
                $component = InventoryComponent::select('id as component_id', 'display_name', 'dimensions', 'icon_path', 'asset_path')
                    ->where('id', $item_component->component_id)
                    ->where('is_active', 1)
                    ->first();
                if ($component) {
                    $component->quantity = $quantity;
                    $data_components[] = $component;
                }
            }
            $inventory_item->components = $data_components;
        }
        return $inventory_items;
    }
}
