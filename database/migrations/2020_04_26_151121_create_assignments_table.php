<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigIncrements('id');

            //多对多
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('task_id')->nullable()->comment('任务ID');

            //TODO: 多维多对多 assignable(可指派的)
            //TODO: assignable_id assignable_type assignable_status assignable_count deadline[时间限制]

            //pivot上的属性
            $table->float('progress', 10, 2)->default(0)->comment('任务进度');
            $table->text('content')->nullable()->comment('内容');
            $table->integer('status')->default(1)->comment('状态 1:已指派 2:已达成 3:已奖励');
            $table->timestamp('completed_at')->nullable()->comment('完成时间');

            //如喝水子任务[1,2,3]代表喝了1，2，3杯
            $table->json('resolve')->nullable()->commit('子任务的进度');
            $table->integer('current_count')->default(0)->commit('当前完成任务的次数');

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
        Schema::dropIfExists('assignments');
    }
}
