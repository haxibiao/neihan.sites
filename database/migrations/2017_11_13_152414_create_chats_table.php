<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('last_message_id')->nullable()->comment('最后一条消息id');
            $table->string('uids')->nullable()->comment('json_encode所有在组内的用户id');
            $table->string('subject')->nullable()->comment('群聊组名');
            $table->string('introduction')->nullable()->comment('群介绍');
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
        Schema::dropIfExists('chats');
    }
}
