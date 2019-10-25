<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->index();
            $table->string('keywords')->nullable();

            //被评论时间
            $table->timestamp('commented')->nullable();

            $table->mediumText('description')->nullable();
            $table->longText('body')->nullable();

            $table->string('author')->nullable();
            $table->integer('author_id')->nullable();

            $table->integer('user_id');
            $table->integer('category_id')->nullable()->index();

            $table->string('image_url')->nullable()->index(); //TODO: 改名cover
            $table->integer('status')->default(0)->index();
            $table->boolean('is_top')->default(0)->index();
            $table->string('source_url')->nullable()->index();
            $table->boolean('has_pic')->default(0)->index(); //TODO: drop, 用属性代替

            $table->string('date')->nullable()->index(); //TODO:drop,以前traffic统计用
            $table->string('user_name')->nullable(); //TODO:drop
            $table->timestamp('edited_at')->nullable();

            $table->integer('hits_mobile')->default(0);
            $table->integer('hits_phone')->default(0);
            $table->integer('hits_wechat')->default(0);
            $table->integer('hits_robot')->default(0);

            $table->string('image_top')->nullable(); //TODO: 删除荣誉，用当前文章的置顶属性，关联图片和图片地址出来

            $table->integer('hits')->default(0);

            $table->integer('count_replies')->default(0);
            $table->integer('count_favorites')->default(0);
            $table->integer('count_shares')->default(0);
            $table->integer('count_tips')->default(0);
            $table->integer('count_likes')->default(0)->index();
            $table->integer('count_words')->default(0);
            $table->integer('count_comments')->nullable(); //comments, not include replies in comments...
            $table->integer('count_reports')->default(0);

            $table->string('slug')->nullable()->unique();

            $table->text('json')->nullable();

            $table->integer('collection_id')->nullable()->index(); //编辑直接发布录入专题时，可能没归属的文集

            $table->unsignedInteger('video_id')
                ->nullable()
                ->index()->comment('视频id');

            $table->string('video_url') //TODO: 应该删除该冗余字段
                ->nullable()
                ->comment('视频的url');

            $table->string('type', 10)
                ->index()
                ->default('article')
                ->comment('内容的类型:article:普通文章，video:视频, post:动态');

            $table->tinyInteger('submit')->default(1)->index()->comment('审核状态: -1 已拒绝, 0 审核中, 1 已收录');
            $table->string('remark')->nullable()->comment('备注');

            $table->softDeletes();

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
        Schema::dropIfExists('articles');
    }
}
