<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $fillable = [
		'path',
		'path_small',
	];

    public function articles() {
    	return $this->belongsToMany('App\Article');
    }
}
