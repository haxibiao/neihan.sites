<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actions extends Model {
	protected $fillable = [
		'user_id',
		'type',
		'object_id',
	];
	public function user() {
		return $this->belongsTo(\App\User::class);
	}
	public function comment() {
		return $this->belongsTo(\App\Comment::class, 'object_id');
	}
	public function tip() {
		return $this->belongsTo(\App\Tip::class, 'object_id');
	}
	public function tag() {
		return $this->belongsTo(\App\Tag::class, 'object_id');
	}
}
