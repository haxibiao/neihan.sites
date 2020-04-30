<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('anchor_id')->comment('主播id');
            $table->string('push_stream_url')->nullable()->comment('推流地址');
            $table->string('push_stream_key')->nullable()->comment('鉴权密钥');
            $table->string('pull_stream_url')->nullable()->comment('拉流地址');
            $table->string('stream_name')->nullable()->comment('流名称');
            $table->string('cover', 100)->nullable();
            $table->unsignedTinyInteger('type')->default(0)->comment('直播间类型: 普通房间（0）答题房间（1）你画我猜房间（2）');
            $table->unsignedInteger('count_audience')->default(0)->comment('直播室观众');
            $table->timestamp('latest_live_time')->nullable()->comment('最近直播时间');
            $table->tinyInteger('status')->default(0)->comment('-1:下直播 -2:直播间被封 0:正常 1:直播中');
            $table->string('title')->nullable();
            $table->timestamps();

            $table->index('anchor_id');
            $table->index('pull_stream_url');
            $table->index('stream_name');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_rooms');
    }
}
