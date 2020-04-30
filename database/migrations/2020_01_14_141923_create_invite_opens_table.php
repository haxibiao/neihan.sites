<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteOpensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //文档: http://pm.haxibiao.com:8080/browse/DDZ-246
        Schema::create('invite_opens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('发邀请的上级用户的id');
            $table->string('user_agent', 1000);
            $table->string('ip', 50)->index()->comment('关键匹配信息靠IP');
            $table->string('os', 50)->index()->comment('OS');
            $table->string('os_version', 50)->index()->comment('OS version');
            $table->string('os_build', 50)->index()->nullable()->comment('OS build');
            $table->string('model', 50)->index()->nullable()->comment('手机型号信息');
            $table->unsignedInteger('installs')->default(0)->comment('一般成功匹配到后installs=1');
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
        Schema::dropIfExists('invite_opens');
    }
}
