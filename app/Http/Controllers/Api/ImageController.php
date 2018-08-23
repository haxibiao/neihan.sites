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
            $image->fillForJs();
        }
        return $images;
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($request->photo) {
            $extension = $request->photo->getClientOriginalExtension();
            if (!in_array($extension, ['jpg', 'png', 'gif', 'jpeg'])) {
                return "图片格式只支持jpg, png, gif";
            }

            $image          = new Image();
            $image->user_id = $user->id;
            $image->save();
            $error = $image->save_file($request->photo);

            if ($error) {
                return $error;
            } else {
                //给simditor编辑器返回上传图片结果...
                if ($request->get('from') == 'simditor') {
                    // $json = "{ success: true, msg:'图片上传成功', file_path: '" . $path_big . "' }";
                    $json            = (object) [];
                    $json->success   = true;
                    $json->msg       = '图片上传成功';
                    $json->file_path = $image->url();
                    return json_encode($json);
                }

                if ($request->from == 'post') {
                    $image->url = $image->url();
                    return $image;
                }

                return $image->url();
            }
        }
        return "没有发现上传的图片photo";
    }
}
