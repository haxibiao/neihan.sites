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
}
