<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->tinyInteger('reward_rate')->default(1)->comment('奖励倍数');

            $table->unsignedInteger('gold_reward')->default(0)->comment('健康分奖励');
            $table->unsignedInteger('contribute_reward')->default(0)->comment('精力点奖励');
            $table->unsignedInteger('keep_checkin_days')->default(0)->comment('连续签到日');
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
        Schema::dropIfExists('check_ins');
    }
}
