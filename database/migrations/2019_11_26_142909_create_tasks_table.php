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
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->comment('任务名称');
                $table->text('details')->nullable()->comment('描述');
                $table->integer('type')->nullable()->comment('类型：0:新人任务 1:每日任务 2:自定义任务 3:实时任务');
                $table->json('reward')->nullable()->comment('奖励');
                $table->unsignedInteger('count')->default(0)->comment('统计');
                $table->string('check_functions')->nullable()->comment('解析函数');
                $table->boolean('status')->default(0)->comment('状态: 0:删除 1:展示');
                $table->timestamp('start_at')->nullable()->comment('开始时间');
                $table->timestamp('end_at')->nullable()->comment('截止时间');
                $table->json('resolve')->nullable()->comment('解析');
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
        Schema::dropIfExists('tasks');
    }
}
