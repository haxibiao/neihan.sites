<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
	protected $table = 'article_image';
	
    protected $fillable = [
    	'article_id',
    	'image_id',
    ];
}
