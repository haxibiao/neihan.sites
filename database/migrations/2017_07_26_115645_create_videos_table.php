<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('path')->nullable();
            $table->string('path_mp4')->nullable();
            $table->string('cover')->nullable();
            $table->text('introduction')->nullable();
            $table->integer('duration')->nullable(); //in seconds
            $table->integer('count')->default(0)->index();
            $table->integer('status')->default(0)->index();
            $table->string('hash')->nullable()->index();
            $table->text('json')->nullable();
            $table->string('adstime')->nullable();
            $table->integer('likes')->default(0)->index();

            //2018-5-24-czg 添加字段 用于 统计视频播放量 
            $table->integer('hits')->default(0);

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
        Schema::dropIfExists('videos');
    }
}
