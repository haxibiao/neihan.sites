<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
	protected $fillable = [
		'name',
		'name_en',
		'description',
		'user_id',
		'parent_id',
		'type',
		'order',
	];

	public function creator() {
		return $this->belongsTo(\App\User::class);
	}
	public function member() {
		return $this->belongsToMany(\App\User::class)->withPivot('approved', 'is_admin');
	}
	public function user() {
		return $this->belongsTo('App\User');
	}

	public function articles() {
		return $this->belongsToMany('App\Article');
	}

	public function parent() {
		return $this->belongsTo(\App\Category::class, 'parent_id');
	}
	public function comments() {
		return $this->hasMany(\App\Comment::class, 'object_id');
	}
	public function follows() {
		return $this->morphMany(\App\Follow::class, 'followed');
	}
	public function videos() {
		return $this->belongsToMany(\App\Video::class);
	}
	public function favorites() {
		return $this->morphMany(\App\Favorite::class, 'faved');
	}
}
