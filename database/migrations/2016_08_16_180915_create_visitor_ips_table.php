<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorIpsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('visitor_ips', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('visitor_id')->unsigned();
            $table->foreign('visitor_id')
                ->references('id')->on('visitors')
                ->onDelete('cascade');

            $table->string('ip')->index();

            $table->bigInteger('geoname_id')->nullable()->index();
            $table->foreign('geoname_id')
                ->references('geoname_id')->on('geolite_locations')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('visitor_ips');
    }
}
