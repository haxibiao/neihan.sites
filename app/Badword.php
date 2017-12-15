<?php

namespace App;

use App\Model;

class Badword extends Model {
	protected $fillable = [
		'type',
		'word',
		'user_id',
	];
}
