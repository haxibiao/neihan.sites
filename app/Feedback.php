<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
		'content',
		'contact',
	];
	public function images() {
		return $this->belongsToMany(\App\Image::class)->withTimestamps();
	}
	public function user() {
		return $this->belongsTo(\App\User::class);
	}
}
