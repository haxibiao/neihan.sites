<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('collections', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('top')->default(0);
			$table->string('description')->nullable();
			$table->string('title');
			$table->string('image_url');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('collections');
	}
}
