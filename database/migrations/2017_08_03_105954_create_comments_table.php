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
            $table->integer('user_id')->index();

            //change to : polymorph relations
            $table->integer('commentable_id')->index();
            $table->string('commentable_type')->index();

            $table->text('body');
            $table->integer('comment_id')->index()->nullable();
            $table->integer('lou')->index()->default(0);
            $table->integer('likes')->index()->default(0); //TODO: 这里重构为count_likes
            $table->integer('reports')->index()->default(0); //TODO: 重构为count_reports

            //for app @user
            $table->integer('at_uid')->index()->nullable();
            $table->integer('status')->index()->nullable();

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
