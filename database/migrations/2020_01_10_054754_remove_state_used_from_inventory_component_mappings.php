<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStateUsedFromInventoryComponentMappings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inventory_component_mappings', function (Blueprint $table) {
            $table->dropColumn(['project_id', 'project_state', 'inventory_used']);
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
