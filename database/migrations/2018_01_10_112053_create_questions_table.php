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
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('title');
            $table->text('background')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->integer('latest_answer_id')->default(0);
            $table->integer('best_answer_id')->default(0);
            $table->integer('count_answers')->default(0);
            $table->integer('count_favorites')->default(0);
            $table->integer('count_reports')->default(0);
            $table->integer('count_likes')->default(0);
            $table->integer('hits')->default(0);
            $table->integer('status')->default(0)->index();

            //pay
            $table->boolean('is_anonymous')->nullable();
            $table->integer('bonus')->nullable();
            $table->integer('deadline')->nullable();
            $table->string('answered_ids')->nullable()->index(); //字段不够长，后期可以ui限制最多选多少个ids

            //closed
            $table->boolean('closed')->default(0)->index();

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
        Schema::dropIfExists('questions');
    }
}
