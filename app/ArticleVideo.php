<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleVideo extends Model
{
	protected $table = 'article_video';
	
    protected $fillable = [
    	'article_id',
    	'video_id',
    ];
}
