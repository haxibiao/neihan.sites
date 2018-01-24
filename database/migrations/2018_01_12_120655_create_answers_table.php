<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('question_id')->index();
            $table->integer('article_id')->index()->nullable();
            $table->longText('answer');
            $table->string('image_url')->index()->nullable();  //save primary image small path
            $table->integer('count_likes')->index()->nullable();
            $table->integer('count_unlikes')->index()->nullable();
            $table->integer('count_comments')->nullable();
            $table->integer('count_reports')->nullable();

            $table->integer('status')->default(1);   // -1 delete  0 disable
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
