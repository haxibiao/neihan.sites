<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用户邀请关系表（好友列表，厂长）
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('邀请人用户ID');
            $table->unsignedInteger('be_inviter_id')->index()->comment('被邀请人ID');
            $table->timestamp('invited_in')->nullable();
            $table->unsignedInteger('app_id')->index()->default(1)->comment('厂长头衔ID');
            $table->unsignedInteger('today_rewards_count')->default(0)->comment('今日奖励次数');

            //TODO: 冗余一个总贡献数，方便ceo查看厂长的总贡献
            $table->unsignedInteger('total_contribute')->default(0)->comment('厂长的总贡献');

            $table->decimal('rate', 5, 2)->default(0)->comment('倍率');
            $table->json('secondary_user_ids')->nullable()->comment('下级用户ID');
            $table->timestamp('next_increment_at')->nullable()->comment('下次增重时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
