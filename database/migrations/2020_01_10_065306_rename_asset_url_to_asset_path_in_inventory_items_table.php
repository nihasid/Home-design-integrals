<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAssetUrlToAssetPathInInventoryItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->renameColumn('asset_url', 'asset_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inventory_items', function (Blueprint $table) {
            //
        });
    }
}
