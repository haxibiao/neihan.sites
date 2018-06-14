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
        $images = $qb->paginate(24);
        foreach ($images as $image) {
            $image->path = $image->url_small();
        }
        return $images;
    }

    public function store(Request $request)
    {
        $user           = $request->user();
        $image          = new Image();
        $image->user_id = $user->id;
        $image->save();
        $image->save_file($request->photo);

        //给simditor编辑器返回上传图片结果...
        if ($request->get('from') == 'simditor') {
            // $json = "{ success: true, msg:'图片上传成功', file_path: '" . $path_big . "' }";
            $json            = (object) [];
            $json->success   = true;
            $json->msg       = '图片上传成功';
            $json->file_path = $image->url();
            return json_encode($json);
        }

        return $image->url();
    }
}
