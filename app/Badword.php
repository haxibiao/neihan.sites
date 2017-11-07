<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badword extends Model {
	protected $fillable = [
		'type',
		'word',
		'user_id',
	];
}
