<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_task')) {
            Schema::create('user_task', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index()->comment('用户ID');
                $table->unsignedInteger('task_id')->nullable()->comment('任务ID');
                $table->float('progress', 10, 2)->default(0)->comment('任务进度');
                $table->text('content')->nullable()->comment('内容');
                $table->integer('status')->default(0)->comment('完成状态 -1:任务失败 0:未完成 1:进行中 2:任务达标未领取奖励 3:任务已完成');
                $table->integer('reward_rate')->default(1)->comment('奖励倍数');
                $table->timestamp('completed_at')->nullable()->comment('完成时间');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_task');
    }
}
