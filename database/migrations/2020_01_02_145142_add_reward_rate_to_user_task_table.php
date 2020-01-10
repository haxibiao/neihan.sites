<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRewardRateToUserTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_task', function (Blueprint $table) {
            if (!Schema::hasColumn('user_task', 'reward_rate')) {
                $table->integer('reward_rate')->default(1)->comment('奖励倍数');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_task', function (Blueprint $table) {
            //
        });
    }
}
