<?php

namespace App;

use App\Model;

class Snippet extends Model
{
    protected $fillable = [
    	'title',
    	'body',
    	'image',
    ];
}
