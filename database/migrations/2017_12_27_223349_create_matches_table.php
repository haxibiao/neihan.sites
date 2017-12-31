<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     //每一场比赛的match
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compare_id')->nullable();
            $table->integer('round');  //比赛轮数.
            $table->string('type');  // 小组赛 or 淘汰赛
            $table->string('score');    //这场比赛的大比分  json记录.
            $table->string('winner');   //记录获胜队伍的名字.
            $table->integer('TA');     //参赛队伍1.
            $table->integer('TB');    //参赛队伍2
            $table->timestamp('start_at')->nullable();   //开赛时间.
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
        Schema::dropIfExists('matches');
    }
}
