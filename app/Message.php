<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {
	public function chat() {
		return $this->belongTo(\App\Chat::class);
	}
}
