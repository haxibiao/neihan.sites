<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
	protected $fillable = [
		'name',
	];

	public function creator() {
		return $this->belongsTo(\App\User::class);
	}
	public function articles() {
		return $this->morphedByMany(\App\Article::class, 'taggable');
	}
	public function videos() {
		return $this->morphedByMany(\App\Video::class, 'taggable');
	}
}
