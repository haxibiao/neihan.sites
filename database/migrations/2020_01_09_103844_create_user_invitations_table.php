<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //TODO: 用户的发展阶段统计表， 叫InvitationPhase
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->unique()->comment('用户ID');
            $table->integer('firends_total_contribute')->default(0)->comment('邀请好友总贡献');
            $table->unsignedInteger('success_firends_count')->default(0)->comment('邀请成功好友数');
            $table->unsignedInteger('firends_count')->default(0)->comment('邀请好友数量');
            $table->unsignedInteger('phase_id')->default(0)->index()->comment('邀请阶段ID');
            $table->decimal('red_packet_phase_amount')->default(0)->comment('红包阶段金额');
            $table->unsignedInteger('red_packet_invites_count')->default(0)->comment('红包阶段邀请人数');
            $table->timestamp('next_increment_at')->nullable()->comment('下次增重时间');
            $table->decimal('rate', 5, 2)->default(0)->comment('倍率');
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
        Schema::dropIfExists('user_invitations');
    }
}
