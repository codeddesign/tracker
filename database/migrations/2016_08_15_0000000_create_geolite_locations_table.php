<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeoliteLocationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('geolite_locations', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('geoname_id')->index();

            $table->string('country')->index();
            $table->string('state')->index();
            $table->string('city')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('geolite_locations');
    }
}
