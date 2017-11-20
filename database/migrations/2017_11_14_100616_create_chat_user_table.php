<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatUserTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('chat_user', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->integer('chat_id')->index();
			$table->text('with_users')->nullable(); //这里返回一个用户的json可能会包含头像和用户名字和id
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('chat_user');
	}
}
