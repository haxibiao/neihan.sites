<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('questions')) {
            Schema::drop("questions");
        }
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 1000)->comment('题目描述');
            $table->json('selections')->comment('题目选项');
            $table->string('answer', 10)->comment('答案');
            $table->integer('gold')->default(0)->comment('智慧点');
            $table->integer('ticket')->default(1)->comment('精力点');
            //关联images表
            $table->unsignedInteger('image_id')->nullable()->index()->comment('主配图');

            $table->unsignedInteger('video_id')->nullable()->index()->comment('视频');

            //关联到categories表
            $table->unsignedInteger('category_id')->nullable()->index()->comment('分类ID');

            //预留type 考虑后期扩展 拼图 语音答题
            $table->string('type', 10)->default('text')->comment('类型：text 文字答题');
            $table->integer('correct_count')->default(0)->index()->comment('统计：正确回答');
            $table->integer('wrong_count')->default(0)->index()->comment('统计：错误回答');
            $table->integer('count_comments')->default(0)->index()->comment('统计：评论数量');
            $table->integer('count_likes')->default(0)->index()->comment('统计：点赞数量');

            //关联到users表
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->integer('submit')->default(0)->index()->comment('提交状态: 1 - 已收录 -2 - 已拒绝 -3 - 已撤回 -1 - 已移除 0 - 待审核 ');
            //关联merchants表
            $table->unsignedInteger('merchant_id')->index()->nullable()->comment('商户ID');

            $table->integer('review_id')->default(0);
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->string('remark')->nullable()->comment('备注');
            $table->tinyInteger('rank')->index()->comment('题目权重');
            $table->tinyInteger('accepted_count')->default(0)->comment('赞成投票数');
            $table->tinyInteger('declined_count')->default(0)->comment('反对投票数');
            $table->boolean('is_rewarded')->default(0)->comment('是否奖励');
            $table->unsignedInteger('explanation_id')->nullable()->comment('解析ID');
            $table->unsignedInteger('answers_count')->default(0)->index()->comment('回答总数');

            $table->string('md5')->nullable()->comment('MD5');
            $table->unsignedInteger('audio_id')->nullable()->comment("音频id");
            $table->unsignedTinyInteger('form')->default(0)->comment('答题形式 0 选择题 1 普通动态, 2问答题');
            $table->boolean('is_resolved')->nullable()->index()->comment('付费问答是否被解决');

            $table->index(['category_id', 'user_id', 'rank']); //for where
            $table->index(['rank', 'review_id']); // for order by
            $table->index(['category_id', 'user_id', 'rank', 'review_id']);
            $table->index('audio_id');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
