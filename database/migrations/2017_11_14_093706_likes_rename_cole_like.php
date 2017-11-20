<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LikesRenameColeLike extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('likes', function (Blueprint $table) {
			$table->renameColumn('object_id', 'liked_id');
			$table->renameColumn('type', 'liked_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('likes', function (Blueprint $table) {
			//
		});
	}
}
