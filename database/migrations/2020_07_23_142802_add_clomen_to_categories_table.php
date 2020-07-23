<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClomenToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->unsignedInteger('questions_count')->default(0)->comment('问题统计');
            $table->unsignedInteger('answers_count')->default(0)->comment('回答统计');
            $table->json('ranks')->nullable()->comment('分类下题目的ranks');
            $table->tinyInteger('allow_submit')->default(1)->comment("系统是否允许用户出题到这个分类");
            $table->unsignedInteger('correct_answer_users_count')->default(0)->comment('答对用户');
            $table->unsignedInteger('min_answer_correct')->default(20)->index()->comment('最小答对数');
            $table->json('answers_count_by_month')->nullable()->comment('每个月的答题次数');
            $table->string('tips')->nullable()->comment('分类tips');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
