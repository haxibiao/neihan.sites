<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\Favorite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller {
	public function save(Request $request, $id, $type) {
		$user = $request->user();
		$favorite = Favorite::firstOrNew([
			'user_id' => $request->user()->id,
			'faved_id' => $id,
			'faved_type' => get_polymorph_types($type),
		]);
		$favorite->save();

		//save user action
		$user->actions()->save(new Action([
			'user_id' => $user->id,
			'actionable_type' => 'favorites',
			'actionable_id' => $favorite->id,
		]));
		return $favorite->id;
	}

	public function delete(Request $request, $id, $type) {
		$favorite = Favorite::firstOrNew([
			'user_id' => $request->user()->id,
			'faved_id' => $id,
			'faved_type' => $type,
		]);
		$favorite->delete();
		return 1;
	}

	public function get(Request $request, $id, $type) {
		$favorite = Favorite::firstOrNew([
			'user_id' => $request->user()->id,
			'faved_id' => $id,
			'faved_type' => $type,
		]);
		return $favorite->id;
	}
}
