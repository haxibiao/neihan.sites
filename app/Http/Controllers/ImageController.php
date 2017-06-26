<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;

class ImageController extends Controller
{
    public function upload(Request $request) {
    	$files = $request->file('image');
    	$index = 1;
    	$paths = [];
    	foreach($files as $file) {
    		$filename = time().'.'.$index.'.jpg';
    		$index++;
    		$path = 'img/'.$filename;
    		$local_path = public_path('img/');
    		$file->move($local_path, $filename);

    		$image = new Image();
    		$image->path = $path;
    		$full_path = $local_path . $filename;
    		$img = \ImageMaker::make($full_path);
    		$img->resize(200, null,function ($constraint) {
			    $constraint->aspectRatio();
			});
    		$img->save($full_path.'.small.jpg');
    		$image->path_small = $path = 'img/'.$filename.'.small.jpg';
    		$image->save();

    		$paths[] = $image->path_small;
    	}

    	return $paths;
    }
}
