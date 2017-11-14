<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FavoritesRenameColeFavorites extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('favorites', function (Blueprint $table) {
			$table->renameColumn('object_id', 'faved_id');
			$table->renameColumn('type', 'faved_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('favorites', function (Blueprint $table) {
			//
		});
	}
}
