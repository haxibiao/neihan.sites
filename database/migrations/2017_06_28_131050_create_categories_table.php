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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('type')->nullable()->comment('类型如：article, video 方便过滤专注某类内容的分类时用');
            $table->string('name');
            $table->string('name_en')->nullable()->comment('可以理解为分类的url 的 slug 部分');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('has_child')->default(0);
            $table->integer('level')->nullable()->index();
            $table->integer('order')->nullable()->index()->comment('分类需要排序时用');

            $table->integer('status')->default(0)->comment('0: 不让投稿, 1: allow');
            $table->integer('request_status')->default(0)->comment('0: 投稿无需审核, 1: need approve');

            //counts
            $table->integer('count_follows')->default(0)->index();
            $table->integer('count')->default(0)->comment('文章数');
            $table->integer('count_questions')->default(0);
            $table->integer('count_videos')->default(0)->comment('视频文章数');

            $table->integer('new_requests')->default(0)->comment('新投稿数');
            $table->string('new_request_title')->nullable()->comment('新投稿标题');

            //APP
            $table->boolean('is_official')->default(0)->comment('APP:是否官方专题');
            $table->boolean('is_for_app')->default(0)->comment('APP:是否在APP首页显示');
            $table->string('logo_app')->nullable()->comment('APP:APP美化后的LOGO图片');

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
