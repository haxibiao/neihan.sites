<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageTag extends Model
{
    protected $table = 'image_tag';
	
    protected $fillable = [
    	'image_id',
    	'tag_id',
    ];

    public function image() {
    	return $this->belongsTo('\App\Image');
    }

    public function tag() {
    	return $this->belongsTo('\App\Tag');
    }
}
