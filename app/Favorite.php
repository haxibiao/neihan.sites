<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {
	protected $fillable = [
		'user_id',
		'object_id',
		'collection_id',
		'type',
	];

	public function article() {
		return $this->belongsTo(\App\Article::class, 'object_id');
	}

	public function video() {
		return $this->belongsTo(\App\Video::class, 'object_id');
	}
	public function collection() {
		return $this->belongsTo(\App\Collection::class, 'collection_id');
	}
}
