<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_counters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('count')->default(0)->comment('总激励视频奖励次数');
            $table->unsignedInteger('count_toutiao')->default(0);
            $table->unsignedInteger('count_tencent')->default(0);
            $table->unsignedInteger('count_baidu')->default(0);
            $table->string('last_provider', 50)->comment('上次轮的平台，切换间隔用');
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
        Schema::dropIfExists('reward_counters');
    }
}
