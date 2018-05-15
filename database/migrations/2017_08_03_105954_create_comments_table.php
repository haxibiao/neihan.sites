<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('at_uid')->index()->nullable();

            //change to : polymorph relations
            $table->integer('commentable_id')->index();
            $table->string('commentable_type')->index();

            $table->text('body');
            $table->integer('comment_id')->index()->nullable();
            $table->integer('lou')->index()->default(0);
            $table->integer('likes')->index()->default(0);
            $table->integer('reports')->index()->default(0);
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
        Schema::dropIfExists('comments');
    }
}
