<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->string('name')->default('')->comment('音频名称');
            $table->unsignedInteger('duration')->default(0)->comment('时长');
            $table->json('json')->nullable()->comment('音频信息');
            $table->string('hash')->default('')->index()->comment('文件hash');
            $table->string('path')->comment('储存地址');
            $table->string('disk', 30)->default('')->omment('磁盘');

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
        Schema::dropIfExists('audio');
    }
}
