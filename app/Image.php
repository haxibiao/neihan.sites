<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
	protected $fillable = [
		'path',
		'path_origin',
		'path_small',
	];

	public function articles() {
		return $this->belongsToMany('App\Article');
	}

	public function user() {
		return $this->belongsTo(\App\User::class);
	}
	public function tips() {
		return $this->belongsTo(\App\Tip::class);
	}
}
