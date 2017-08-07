<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'object_id',
        'type',
    ];

    public function article() {
    	return $this->belongsTo(\App\Article::class, 'object_id');
    }

    public function video() {
    	return $this->belongsTo(\App\Video::class, 'object_id');
    }
}
