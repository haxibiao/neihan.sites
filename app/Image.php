<?php

namespace App;

use App\Model;
use App\Traits\PhotoTool;

class Image extends Model
{ 
    use PhotoTool;

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
    /* --------------------------------------------------------------------- */
    /* ------------------------------- service ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function fillForJs()
    {
        $this->url       = $this->url();
        $this->url_small = $this->url_small();
    }

}
