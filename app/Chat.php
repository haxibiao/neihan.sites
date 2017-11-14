<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
	protected $fillable = [
		'uid',
	];
	public function messages() {
		return $this->hasMany(\App\Message::class);
	}
	public function users() {
		return belongsTomay(\App\User::class);
	}
}
