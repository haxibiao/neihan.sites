<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model {
	protected $fillable = [
		'user_id',
		'actionable_type',
		'actionable_id',
	];

	public function actionable() {
		return $this->morphTo();
	}
}
