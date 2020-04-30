<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('invitation_id')->index()->comment('邀请ID');
            $table->string('source', 60)->default('')->comment('来源');
            $table->decimal('reward', 10, 2)->default(0)->comment('奖励');
            $table->string('reward_type', 60)->default('')->comment('奖励类型');
            $table->unsignedInteger('reward_id')->default(0)->index()->comment('奖励ID');
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
        Schema::dropIfExists('invitation_rewards');
    }
}
