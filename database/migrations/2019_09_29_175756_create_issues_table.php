<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //web专用付费问答
        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->text('background')->nullable();
            $table->unsignedInteger('latest_resolution_id')->nullable();
            $table->unsignedInteger('best_resolution_id')->nullable();

            //利用软删除实现状态
            //$table->unsignedInteger('status')->default(0);

            //pay
            $table->boolean('is_anonymous')->default(false)->comment('是否匿名问答');
            $table->decimal('bonus')->nullable()->commit('赏金');
            $table->smallInteger('deadline')->nullable()->commit('悬赏时间');

            $table->unsignedInteger('hits')->default(0);
            $table->unsignedInteger('count_answers')->default(0);
            $table->integer('count_favorites')->default(0);
            $table->integer('count_reports')->default(0);
            $table->integer('count_likes')->default(0);

            //closed
            $table->boolean('closed')->default(false)->comment('问题是否解决');

            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('resolution_ids')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('closed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
