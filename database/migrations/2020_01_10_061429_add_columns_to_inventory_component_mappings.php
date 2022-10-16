<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInventoryComponentMappings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inventory_component_mappings', function (Blueprint $table) {
            $table->string('quantity')->after('id');
            $table->string('component_id')->after('id');
            $table->string('item_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inventory_component_mappings', function (Blueprint $table) {
            //
        });
    }
}
