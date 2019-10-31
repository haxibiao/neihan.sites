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
            $table->integer('user_id')->default(0)->index();
            $table->string('title')->nullable();
            $table->string('path')->nullable();
            $table->integer('duration')->default(0)->comment('时长秒');
            $table->integer('status')->default(0)->index()->comment('-1删除 0隐藏 1发布');
            $table->string('hash')->nullable()->unique()->comment('避免重复上传文件');

            $table->string('cover')->nullable()->comment('截图封面,disk同video的disk');
            $table->text('json')->nullable();

            // $table->string('adstime')->nullable(); //无地方用，rename为 disk 用来标记视频成功存储位置
            $table->string('disk')->nullable()->comment('存储位置 local,vod,cos等');

            $table->timestamps();
            $table->softDeletes();

            $table->string('qcvod_fileid')->nullable()->index()->comment('disk在vod的时候有用');
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
