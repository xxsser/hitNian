<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamedatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamedatas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fan_id')->unsigned();
            $table->integer('damages')->unsigned()->default('0');
            $table->integer('coins')->unsigned()->default('0');
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
        //
        Schema::drop('gamedatas');
    }
}
