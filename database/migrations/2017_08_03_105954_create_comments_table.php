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
            $table->integer('commentable_id')->index();
            $table->string('commentable_type')->index();

            $table->text('body');
            $table->integer('comment_id')->index()->nullable()->comment('楼中楼id');
            $table->integer('lou')->index()->default(0);

            $table->integer('count_likes')->index()->default(0);
            $table->integer('count_reports')->index()->default(0);

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
