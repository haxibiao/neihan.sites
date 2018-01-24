<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('count_answers')->nullable();
            $table->integer('count_favorites')->nullable();
            $table->integer('count_comments')->nullable();
            $table->integer('count_likes')->nullable();
            
            $table->integer('latest_answer_id')->nullable();
            $table->integer('status')->default(1);   // -1 delete  0 disable
            $table->integer('hits')->nullable();
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
