<?php

namespace App;

use App\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function articles()
    {
        return $this->morphedByMany('App\Article', 'taggable')->exclude(['body', 'json']);
    }

    public function videos()
    {
        return $this->morphedByMany('App\Video', 'taggable');
    }
}
