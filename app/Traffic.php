<?php

namespace App;

use App\Model;

class Traffic extends Model
{
    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }

    public function path()
    {
    	if($this->path == 'browseIndex'){
    		$this->path = 'APP首页浏览';
    	}
    	return $this->path;
    }
}
