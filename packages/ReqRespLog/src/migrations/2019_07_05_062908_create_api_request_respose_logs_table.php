<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiRequestResposeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_response_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->text('access_token')->nullable();
            $table->string('request_id')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->string('method')->nullable();
            $table->integer('http_status')->nullable();
            $table->text('request_data')->nullable();
            $table->text('response_data')->nullable();
            $table->text('request_headers')->nullable();
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_response_logs');
    }
}
