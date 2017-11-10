<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('follows', function (Blueprint $table) {
			$table->increments('id');
			$table->string('type')->index(); // user or collection or category
			$table->integer('user_id');
			$table->integer('follwing_user_id');
			$table->integer('follwing_collection_id');
			$table->integer('follwing_category_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('follows');
	}
}
