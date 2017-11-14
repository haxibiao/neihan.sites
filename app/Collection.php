<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {
	protected $fillable = [
		'user_id',
		'status',
		'type',
		'name',
		'logo',
	];
	public function comments() {
		return $this->hasMany(\App\Comment::class, 'object_id');
	}
	public function articles() {
		return $this->belongsToMany(\App\Article::class);
	}
	public function user() {
		return $this->belongsTo(\App\User::class);
	}
	public function follows() {
		return $this->morphMany(\App\Follow::class, 'followed');
	}
}
