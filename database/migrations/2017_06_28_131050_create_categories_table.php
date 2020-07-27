<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60)->comment('分类名称');
            $table->string('description')->nullable()->default('这个分类暂无描述哦！')->comment('分类描述');
            $table->string('icon')->nullable()->comment('图标');
            $table->unsignedInteger('questions_count')->default(0)->comment('问题统计');
            $table->unsignedInteger('answers_count')->default(0)->comment('回答统计');
            $table->unsignedInteger('parent_id')->nullable()->comment('父级分类');
            $table->boolean('status')->default(1)->index()->comment('状态: 删除(-1) 隐藏(0) 正常(1)');
            $table->boolean('is_official')->default(0)->index()->comment('是否官方分类:  否(0) 是(1)');
            $table->unsignedInteger('rank')->default(0)->comment('排名');
            $table->json('ranks')->nullable()->comment('分类下题目的ranks');
            $table->tinyInteger('allow_submit')->default(1)->comment("系统是否允许用户出题到这个分类");
            $table->unsignedInteger('correct_answer_users_count')->default(0)->comment('答对用户');
            $table->unsignedInteger('min_answer_correct')->default(20)->index()->comment('最小答对数');
            $table->json('answers_count_by_month')->nullable()->comment('每个月的答题次数');
            $table->string('tips')->nullable()->comment('分类tips');
            $table->string('name_en')->nullable()->comment('可以理解为分类的url 的 slug 部分');
            $table->boolean('has_child', 1)->default(0);
            $table->integer('order')->nullable()->index()->comment('分类需要排序时用');
            $table->integer('level')->nullable()->index();
            $table->integer('count_follows')->default(0)->index();
            $table->integer('count')->default(0)->comment('文章数');
            $table->integer('count_questions')->default(0);
            $table->integer('count_videos')->default(0)->comment('视频文章数');
            $table->boolean('is_for_app')->default(0)->comment('APP:是否在APP首页显示');
            $table->string('logo_app')->nullable()->comment('APP:APP美化后的LOGO图片');
            $table->string('new_request_title')->nullable()->comment('新投稿标题');
            $table->integer('new_requests')->default(0)->comment('新投稿数');
            $table->integer('request_status')->default(0)->comment('0: 投稿无需审核, 1: need approve');
            //关联users表
            $table->unsignedInteger('user_id')->index()->comment('用户ID');

            $table->string('logo')->nullable();

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
        Schema::dropIfExists('categories');
    }
}
