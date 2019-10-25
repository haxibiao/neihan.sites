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
        if(Schema::hasTable('questions')){
            return;
        }
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('description', 1000)->comment('题目描述');
            $table->json('selections')->comment('题目选项');
            $table->string('answer', 10)->comment('答案');
            $table->integer('gold')->default(0)->comment('智慧点');
            $table->integer('ticket')->default(1)->comment('精力点');

            //关联关系
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->unsignedInteger('image_id')->nullable()->index()->comment('主配图');
            $table->unsignedInteger('video_id')->nullable()->index()->comment('视频');
            $table->unsignedInteger('category_id')->nullable()->index()->comment('分类ID');

            //预留type 考虑后期扩展 拼图 语音答题
            $table->tinyInteger('type')->default(0)->index()->comment('类型');
            $table->tinyInteger('submit')->default(0)->index()->comment('提交状态');
            $table->tinyInteger('rank')->index()->comment('题目权重');

            //冗余字段
            $table->unsignedInteger('correct_count')->default(0)->comment('统计：正确回答');
            $table->unsignedInteger('wrong_count')->default(0)->comment('统计：错误回答');
            $table->unsignedInteger('count_comments')->default(0)->comment('统计：评论数量');
            $table->unsignedInteger('count_likes')->default(0)->comment('统计：点赞数量');
            $table->unsignedInteger('answers_count')->default(0)->comment('回答总数');

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
