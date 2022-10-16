<?php

use App\Helpers\Helper;
use App\Models\ExceptionLog;
use App\Models\InventoryComponent;
use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            $inventoryItemsFile = database_path('seeds/files/inventory_items.csv');
            $inventoryItems = Helper::csvToArray( $inventoryItemsFile );
            InventoryItem::truncate();
            InventoryItem::insert($inventoryItems);

        } catch (Exception $e) {
            ExceptionLog::log($e, static::class);
        }
    }
}
