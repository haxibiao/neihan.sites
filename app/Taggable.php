<?php

namespace App;


class Taggable extends Model
{

    protected $table = 'taggables';
    public $guarded = [];


    public function taggable()
    {
        return $this->morphTo();
    }

    public function tag()
    {
        return $this->belongsTo(\App\Tag::class);
    }
}