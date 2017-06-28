<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function category() {
    	return $this->belongsTo('App\Category');
    }

    public function tags() {
    	return $this->belongsToMany('App\Tag');
    }

    public function images() {
    	return $this->belongsToMany('App\Image');
    }
}
