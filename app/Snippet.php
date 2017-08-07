<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = [
    	'title',
    	'content',
    	'image',
    ];
}
