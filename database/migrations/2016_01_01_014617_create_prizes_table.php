<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('rank')->unsigned();        //奖品等级
            $table->string('name');                         //奖品名称
            $table->integer('num')->unsigned();             //奖品总数量
            $table->integer('pool')->unsigned();            //奖池数量
            $table->integer('denominator')->unsigned();     //奖品概率(分母) 越大概率越小
            $table->integer('factor')->unsigned();          //奖品概率(分子) 越大概率越大,等于denominator时中奖率为100%
            $table->integer('rate')->unsigned();            //奖品投放频率
            $table->enum('type',['drop','rank']);           //奖品类型(掉落，排名)
            $table->timestamps();
        });*/
        Schema::create('prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('rank')->unsigned();            //奖品等级
            $table->string('name');                             //奖品名称
            $table->integer('num')->unsigned();                 //奖品剩余
            $table->integer('coin')->unsigned()->default('0');  //需要金币数量
            $table->enum('type',['exchange','rank']);           //奖品类型(掉落，排名)
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
        Schema::drop('prizes');
    }
}
