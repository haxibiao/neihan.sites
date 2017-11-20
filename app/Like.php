<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
