<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level')->index()->default(0)->comment("层级");
            $table->integer('at_uid')->index()->nullable()->comment("@指定 user_id");
            $table->integer('user_id')->index()->comment("User表主键");
            $table->integer('comment_id')->index()->nullable()->comment('评论父ID');
            $table->integer('count_likes')->index()->default(0)->comment("点赞数");
            $table->integer('count_reports')->index()->default(0)->comment("举报数");
            $table->integer('commentable_id')->index()->comment("多态id，例如：1，则代表 article 表 id 为 1 的文章");

            $table->string('commentable_type')->index()->comment("类型，例如：article");
            $table->text('body')->comment("评论正文");

            //for app @user
            $table->boolean('status')->index()->nullable()->comment("我也不知道这个字段什么意思");
            //评论被采纳
            $table->boolean('is_accept')->default(false)->comment('是否被采纳');

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
        Schema::dropIfExists('comments');
    }
}
