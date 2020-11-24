<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSticksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sticks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('片名，置顶电影 | 最新韩剧 | 经典韩剧');
            $table->string('page')->nullable()->comment('页面');
            $table->string('area')->nullable()->comment('位置｜上下左右');
            $table->string('item_type')->comment('类型：article图文｜video短视频｜movie电影');
            $table->string('item_id')->comment('内容id');
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
        Schema::dropIfExists('sticks');
    }
}
