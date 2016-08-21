<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('visitor_id')->unsigned();
            $table->foreign('visitor_id')
                ->references('id')->on('visitors')
                ->onDelete('cascade');

            $table->integer('link_id')->unsigned()->nullable();
            $table->foreign('link_id')
                ->references('id')->on('links')
                ->onDelete('cascade');

            $table->integer('referer_id')->unsigned()->nullable();
            $table->foreign('referer_id')
                ->references('id')->on('links')
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
        Schema::drop('visits');
    }
}
