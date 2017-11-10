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

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function articles() {
		return $this->hasMany('App\Article');
	}

	public function parent() {
		return $this->belongsTo(\App\Category::class, 'parent_id');
	}
	public function comments() {
		return $this->hasMany(\App\Comment::class, 'object_id');
	}
}
