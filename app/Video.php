<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {
	protected $fillable = [
		'user_id',
		'category_id',
		'title',
		'path',
		'path_mp4',
		'cover',
		'introduction',
		'duration',
		'hash',
		'adstime',
	];

	public function category() {
		return $this->belongsTo(\App\Category::class);
	}
	public function categories() {
		return $this->belongsToMany(\App\Category::class);
	}

	public function articles() {
		return $this->belongsToMany(\App\Article::class);
	}

	public function user() {
		return $this->belongsTo(\App\User::class);
	}
	public function comments() {
		return $this->morphMany(\App\Comment::class, 'commentable');
	}
	public function tips() {
		return $this->morphMany(\App\Tip::class, 'tipable');
	}
}
