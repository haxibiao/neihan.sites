<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $images = Image::orderBy('id', 'desc')->paginate(12);
        return $images;
    }

    public function store(Request $request)
    {
        $controller = new \App\Http\Controllers\ImageController();
        return $controller->store($request);
    }

    public function poster()
    {
        $images  =Image::orderBy('id','desc')->take(8)->get();
        foreach($images as $image){
            $image->path_top();
        }
        $images=$images->pluck('title','path');
        foreach ($images as $path=>$title) {
             $img=[$title,$path];
             $imgs[]=$img;
        }
        return $imgs;
    }
}
