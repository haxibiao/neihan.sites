<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $qb = Image::orderBy('updated_at', 'desc');
        if (request('q')) {
            $qb = $qb->where('title', 'like', '%' . request('q') . '%');
        }
        $images = $qb->paginate(12);
        foreach($images as $image) {
            $image->path = $image->url_small();
        }
        return $images;
    }

    public function store(Request $request)
    {
        $controller = new \App\Http\Controllers\ImageController();
        return $controller->store($request);
    }
}
