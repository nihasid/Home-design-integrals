<?php

use App\Helpers\Helper;
use App\Models\ExceptionLog;
use App\Models\InventoryComponent;
use Illuminate\Database\Seeder;

class InventoryComponentSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        try {
            $inventoryComponentFile = database_path('seeds/files/inventory_components.csv');
            $inventoryComponents = Helper::csvToArray($inventoryComponentFile);

            InventoryComponent::truncate();
            InventoryComponent::insert($inventoryComponents);
        } catch (Exception $e) {
            ExceptionLog::log($e, static::class);
        }
    }
}
