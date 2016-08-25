<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorOldIdsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('visitor_old_ids', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('new_id')->unsigned();
            $table->foreign('new_id')
                ->references('id')->on('visitors')
                ->onDelete('cascade');

            $table->integer('old_id')->unsigned();
            $table->foreign('old_id')
                ->references('id')->on('visitors')
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
        Schema::drop('visitor_old_ids');
    }
}
