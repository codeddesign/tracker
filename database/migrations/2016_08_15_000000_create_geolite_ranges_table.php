<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeoliteRangesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('geolite_ranges', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('geoname_id')
                ->index();

            $table->string('ip_network', 20)
                ->index();

            $table->bigInteger('ip_start')
                ->index();

            $table->bigInteger('ip_end')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('geolite_ranges');
    }
}
