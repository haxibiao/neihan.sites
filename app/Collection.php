<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {
	protected $fillable = [
		'user_id',
		'description',
		'title',
		'image_url',
		'top',
	];
	public function comments() {
		return $this->hasMany(\App\Comment::class, 'object_id');
	}
	public function articles() {
		return $this->belongsToMany(\App\Article::class);
	}
}
