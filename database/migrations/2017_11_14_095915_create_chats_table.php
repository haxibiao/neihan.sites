<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('chats', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('last_message_id')->nullable();
			$table->string('uids')->nullable(); //使用json来保存聊天室存在的用户
			$table->string('subject')->nullable();
			$table->string('introduction')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('chats');
	}
}
