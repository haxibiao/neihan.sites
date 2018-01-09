<?php

namespace App;

use App\Model;

class Image extends Model
{
    protected $fillable = [
        'path',
        'path_origin',
        'path_small',
        'extension',
    ];

    public function articles()
    {
        return $this->belongsToMany('App\Article');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function tips()
    {
        return $this->belongsTo(\App\Tip::class);
    }

    public function path_small()
    {
        return str_replace($this->extension, 'small.' . $this->extension, $this->path);
    }

    public function path_top()
    {
        return str_replace($this->extension, 'top.' . $this->extension, $this->path);
    }
}
