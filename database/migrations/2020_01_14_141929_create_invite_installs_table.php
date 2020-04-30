<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteInstallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_installs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('受邀请的下级用户的id,先记录,方便后续修正');
            $table->unsignedInteger('invite_open_id')->index()->nullable()->comment('成功匹配到的invite_open的id');
            $table->string('user_agent');
            $table->string('ip', 50)->index()->comment('关键匹配信息靠IP');
            $table->string('os', 50)->index()->comment('OS');
            $table->string('os_version', 50)->index()->comment('OS version');
            $table->string('os_build', 50)->index()->nullable()->comment('OS build');
            $table->string('model', 50)->index()->nullable()->comment('手机型号信息');
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
        Schema::dropIfExists('invite_installs');
    }
}
