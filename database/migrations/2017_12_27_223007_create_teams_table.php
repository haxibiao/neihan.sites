<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('compare_id')->nullable();
            $table->string('type')->nullable();//胜者组,败者组.
            $table->integer('team_score')->default(0);  //队伍得分.
            $table->boolean('out')->default(0);        //是否出局,默认该队伍被创建的时候是在场的.
            $table->string('description')->nullable();
            $table->string('group')->nullable();
            $table->string('history')->nullable();  //统计比赛历史.
            $table->integer('status')->default(1);   // 1正常  0被ban不能上场.
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
        Schema::dropIfExists('teams');
    }
}
