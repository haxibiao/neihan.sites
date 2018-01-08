<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function store(Request $request) {
		$controller = new \App\Http\Controllers\ImageController();
		return $controller->store($request);
	}
}
