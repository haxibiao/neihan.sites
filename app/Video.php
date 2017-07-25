<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'path',
        'path_mp4',
        'cover',
        'introduction',
    ];
}
