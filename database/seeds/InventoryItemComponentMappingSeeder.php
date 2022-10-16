<?php

use App\Helpers\Helper;
use App\Models\ExceptionLog;
use App\Models\InventoryComponentMapping;
use Illuminate\Database\Seeder;

class InventoryItemComponentMappingSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        try {
            $inventoryComponentMappingFile = database_path('seeds/files/item_component_mappings.csv');
            $inventoryItemComponentMapping = Helper::csvToArray($inventoryComponentMappingFile);

            InventoryComponentMapping::truncate();
            InventoryComponentMapping::insert($inventoryItemComponentMapping);
        } catch (Exception $e) {
            ExceptionLog::log($e, static::class);
        }
    }
}
