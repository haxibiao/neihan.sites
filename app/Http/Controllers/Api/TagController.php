<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tag;
use App\Image;
use App\ImageTag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('updated_at', 'desc')->paginate(12);
        return $tags;
    }

    public function images($tag_name)
    {
    	$tag = Tag::where('name', $tag_name)->firstOrFail();
    	$image_tags = ImageTag::with('image')->where('tag_id', $tag->id)->paginate(12);   
    	foreach ($image_tags as $image_tag) {
    		$image = $image_tag->image;
            $image->path       = get_img($image->path);
            $image->path_small = get_img($image->path_small);
        } 	
    	return $image_tags;
    }
}
