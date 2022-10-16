<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStageIdInProjectStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_stages', function (Blueprint $table) {
            $table->bigInteger('stage_id')->after('project_id');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_stages', function (Blueprint $table) {
            $table->string('name')->after('project_id');
            $table->dropColumn('stage_id');
        });
    }
}
