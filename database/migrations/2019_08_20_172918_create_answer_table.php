<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('answer')){
            return;
        }
        Schema::create('answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            //关联users表
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            //关联questions表
            $table->unsignedInteger('question_id')->index()->comment('题库ID');

            $table->integer('answered_count')->default(0)->comment('回答次数');
            $table->integer('correct_count')->default(0)->comment('统计：正确回答');
            $table->integer('wrong_count')->default(0)->comment('统计：错误回答');
            $table->unsignedInteger('gold_awarded')->default(0)->comment('总获智慧点');
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
        Schema::dropIfExists('answers');
    }
}
