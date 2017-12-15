<?php

namespace App;

use App\Model;

class Like extends Model {
	protected $fillable = [
		'user_id',
		'liked_id',
		'liked_type',
	];

	public function liked() {
		return $this->morphTo();
	}
}
