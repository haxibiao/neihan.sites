<?php

namespace App;

use App\Model;

class Traffic extends Model
{
    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }
}
