<?php

namespace App;

use App\Model;

class Image extends Model
{
    protected $fillable = [
        'path',
        'path_origin',
        'path_small',
    ];

    public function articles()
    {
        return $this->belongsToMany('App\Article');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function url_prod()
    {
        return env('APP_URL') . $this->path;
    }

    public function url_small()
    {
        //TODO:: use disk local, hxb, cos ...
        if ($this->disk) {
            return env('HXB_URL') . $this->path_small();
        }
        return env('APP_URL') . $this->path_small();
    }

    public function path_small()
    {
        return str_replace($this->extension, 'small.' . $this->extension, $this->path);
    }

    public function path_top()
    {
        return str_replace($this->extension, 'top.' . $this->extension, $this->path);
    }

    public function fillForJs()
    {
        $this->path       = $this->url_prod();
        $this->path_small = $this->url_small();
    }
}
