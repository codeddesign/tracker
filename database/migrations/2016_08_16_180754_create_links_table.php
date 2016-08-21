<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('domain_id')->unsigned();
            $table->foreign('domain_id')
                ->references('id')->on('domains')
                ->onDelete('cascade');

            $table->boolean('is_secure')->default(false)->index();

            $table->boolean('is_www')->default(false)->index();

            $table->text('path');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('links');
    }
}
