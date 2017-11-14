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
			$table->integer('user_id')->index();
			$table->integer('status')->index(); //status  is  0 private or 1 public
			$table->string('type')->default('article')->index();
			$table->string('name');
			$table->string('logo')->nullable();
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
