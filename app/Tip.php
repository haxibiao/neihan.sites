<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model {
	protected $fillable = [
		'user_id',
		'object_id',
		'type',
		'money',
	];
	public function user() {
		return $this->belongsTo(\App\User::class);
	}
	public function articles() {
		return $this->belongsTo(\App\Article::class, 'object_id');
	}
	public function images() {
		return $this->belongsTo(\App\Image::class, 'object_id');
	}
	public function videos() {
		return $this->belongsTo(\App\Video::class, 'object_id');
	}
}
