<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('user_id');

            $table->integer('count_approved')->default(0)->comment('用户在该专题的投稿成功数');
            $table->boolean('is_admin')->default(0)->comment('是否该专题管理');

            $table->unique(['user_id', 'category_id']);
            $table->tinyInteger('in_rank')->nullable()->comment('用户在该分类正在作答的权重指针');
            $table->json('rank_ranges')->nullable()->comment('用户在该分类的每个rank的min_review_id,max_review_id');

            //用户题库的关系pivot 数据
            $table->integer('min_review_id')->nullable()->comment('用户在该题库已答题最小Review ID');
            $table->integer('max_review_id')->nullable()->comment('用户在该题库已答题最大Review ID');
            $table->integer('correct_count')->default(0)->index()->comment('用户在该题库答对题数');
            $table->integer('answer_count')->default(0)->index()->comment('用户在该题库答题数');
            $table->integer('reviews_today')->default(0)->comment('用户在该题库今日审题数');

            $table->unsignedBigInteger('answers_count_today')->default(0)->comment('每日答题数');
            $table->timestamp('last_answer_at')->nullable()->comment('最后一次答题时间');

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
        Schema::dropIfExists('category_user');
    }
}
