<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {
	protected $fillable = [
		'user_id',
		'object_id',
		'type',
	];

	public function article() {
		return $this->belongsTo(\App\Article::class, 'object_id');
	}

	public function video() {
		return $this->belongsTo(\App\Video::class, 'object_id');
	}
	public function comment() {
		return $this->belongsTo(\App\Comment::class, 'object_id');
	}
	public function category() {
		return $this->belongsTo(\App\Category::class, 'object_id');
	}
}
