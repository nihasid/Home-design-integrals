<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsInProjectStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_stages', function (Blueprint $table) {

            $table->boolean('is_issue')->default(0)->after('status');
            $table->bigInteger('user_id')->nullable()->after('is_issue');
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
            //
            $table->dropColumn('is_issue');
            $table->dropColumn('user_id');
        });
    }
}
