<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'background',
    ];

    public function articles()
    {
        return $this->belongsToMany(\App\Article::class);
    }

    public function answer()
    {
        return $this->hasMany(\App\Answer::class);
    }
}
