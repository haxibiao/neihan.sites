<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommentRenameColeComment extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('comments', function (Blueprint $table) {
			$table->renameColumn('object_id', 'commentable_id');
			$table->renameColumn('type', 'commentable_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('comments', function (Blueprint $table) {
			//
		});
	}
}
