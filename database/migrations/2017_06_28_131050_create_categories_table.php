<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('categories', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('name_en');
			$table->string('description')->nullable();
			$table->string('logo')->nullable()->index();
			$table->integer('count')->default(0);
			$table->integer('user_id')->default(0)->index();
			$table->integer('parent_id')->default(0)->index();
			$table->boolean('has_child')->default(0)->index();
			$table->integer('level')->default(0)->index();
			$table->integer('status')->default(0)->index();
			$table->string('type')->nullable()->index();
			$table->integer('order')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('categories');
	}
}
