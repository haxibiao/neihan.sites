<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$user = User::firstOrNew([
			'email' => 'author_test@haxibiao.com',
		]);
		$user->name = '江湖小郎中';
		$user->password = bcrypt('mm1122');
		$user->save();
	}
}
