<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStickablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //重构自秀儿的sticks
        Schema::dropIfExists('sticks');

        Schema::create('stickables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('按名称检索：首页轮播| 置顶电影 | 最新韩剧 | 经典韩剧 | 电影顶楼的推荐');
            $table->string('page')->nullable()->comment('按页面检索：首页 | 发现 | 视频 | 电影频道...');
            $table->string('area')->nullable()->comment('按位置减速：上下左右，需要配合页面检索');
            $table->string('item_type')->comment('类型：articles 图文｜videos 短视频｜movies 电影');
            $table->string('item_id')->comment('内容id');
            $table->tinyInteger('site_id')->nullable()->comment('站群站点');
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
        Schema::dropIfExists('stickables');
    }
}
