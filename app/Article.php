<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {
	protected $fillable = [
		'title',
		'keywords',
		'description',
		'author',
		'author_id',
		'user_name',
		'user_id',
		'category_id',
		'body',
		'image_url',
		'is_top',
		'status',
	];

	protected $dates = ['edited_at'];

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function category() {
		return $this->belongsTo('App\Category');
	}
	public function categories() {
		return $this->belongsToMany(\App\Category::class);
	}
	public function tags1() {
		return $this->belongsToMany(\App\Tag::class);
	}

	public function tags() {
		return $this->morphToMany('App\Tag', 'taggable');
	}

	public function images() {
		return $this->belongsToMany('App\Image');
	}

	public function videos() {
		return $this->belongsToMany('App\Video');
	}
	public function collections() {
		return $this->belongsToMany(\App\Collection::class);
	}
	public function commments() {
		return $this->morphMany(\App\Comment::class, 'commentable');
	}
	public function tips() {
		return $this->morphMany(\App\Tip::class, 'tipable');
	}
}
