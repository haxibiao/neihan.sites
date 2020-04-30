<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tasks')) {
            return;
        }
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('任务名称');
            $table->text('details')->nullable()->comment('描述');
            $table->string('icon')->nullable()->comment("任务图标");
            $table->integer('type')->nullable()->comment('类型：0:新人任务 1:每日任务 2:自定义任务 3:实时任务');

            //TODO: 配合 assignable_type 简化 review_flow 里的  checkTaskStatus 代码 和 check_functions

            $table->json('reward')->nullable()->comment('奖励');
            $table->integer('review_flow_id')->nullable()->commit('任务模版ID');
            $table->integer('max_count')->commit('最多完成的次数');
            $table->boolean('status')->default(0)->comment('状态: 0:删除 1:展示');
            $table->timestamp('start_at')->nullable()->comment('开始时间');
            $table->timestamp('end_at')->nullable()->comment('截止时间');
            $table->json('resolve')->nullable()->comment('解析');
            $table->string('background_img')->nullable()->comment('任务背景图');
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
        Schema::dropIfExists('tasks');
    }
}
