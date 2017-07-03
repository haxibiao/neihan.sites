<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $fillable = [
		'name',
		'name_en',
		'description',
		'user_id',
	];

	public function user() {
		return $this->belongsTo('App\User');
	}

    public function articles() {
    	return $this->hasMany('App\Article');
    }
}
