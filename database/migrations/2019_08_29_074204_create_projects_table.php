<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->bigInteger('shop_id')->index();
            $table->bigInteger('vendor_id')->index();
            $table->bigInteger('region_id')->index();
            $table->bigInteger('country_id')->index();
            $table->string('project_generation');
            $table->enum('project_status', ['Ongoing', 'Completed', 'Overdue']);
            $table->boolean('is_active')->default(true);
            $table->bigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
