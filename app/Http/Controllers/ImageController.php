<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->jsonIndex($request);
        }

        $images = image::with('user')->orderBy('id', 'desc')->paginate(10);
        return view('image.index')->withImages($images);
    }

    public function create()
    {
        return 'not imple...';
    }

    public function store(Request $request)
    {
        ini_set('memory_limit', '256M');

        if ($request->ajax()) {
            return $this->jsonStore($request);
        }
    }

    public function show($id)
    {
        $image = Image::find($id);
        return $image;
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($deleteUrl)
    {
        return 'not implemented deleting ... ' . $deleteUrl;
    }

    public function jsonIndex($request)
    {
        $data      = [];
        $delete_id = $request->get('d');
        if (!empty($delete_id)) {
            //删除
            $image = Image::find($delete_id);
            if ($image) {
                $image->status = -1;
                $image->save();
            }
            $path = $image->path;
            // if (file_exists(public_path($path))) {
            //     unlink(public_path($path));
            // }
            $data[$path] = true;
            $path_small  = $image->path_small();
            // if (file_exists(public_path($path_small))) {
            //     unlink(public_path($path_small));
            // }
            $data[$path_small] = true;
        } else {
            //记载当前用户已上传，未使用的图片
            $query = Image::where('status', '>=', 0)->where('count', 0);
            if (Auth::check()) {
                $user_id = Auth::check() ? Auth::user()->id : 0;
                $query   = $query->where('user_id', $user_id);
            }
            $images = $query->get();
            foreach ($images as $image) {
                $extension = pathinfo(public_path($image->path), PATHINFO_EXTENSION);
                $filename  = pathinfo($image->path)['basename'];
                $file      = [
                    'url'          => base_uri() . $image->path,
                    'thumbnailUrl' => base_uri() . $image->path_small,
                    'name'         => $filename,
                    'id'           => $image->id,
                    "type"         => str_replace("jpg", "jpeg", "image/" . $extension),
                    "size"         => 0,
                    'deleteUrl'    => url('/image?d=' . $image->id),
                    "deleteType"   => "GET",
                ];
                $data['files'][] = $file;
            }
        }
        return $data;
    }

    public function jsonStore($request)
    {
        $user   = $request->user();
        $images = $request->file('files');

        $images = $request->file('files');
        $files  = [];
        foreach ($images as $file) {

            $image          = new Image();
            $image->user_id = $user->id;
            $image->save();
            $image->save_file($file);

            //for jquery multiple uplpad plugin...
            $files[] = [
                'url'          => base_uri() . $image->path,
                'thumbnailUrl' => base_uri() . $image->path_small(),
                'name'         => $image->path,
                'id'           => $image->id,
                "type"         => str_replace("jpg", "jpeg", "image/" . $extension),
                "size"         => filesize(public_path($image->path)),
                'deleteUrl'    => url('/image?d=' . $image->id),
                "deleteType"   => "GET",
            ];
        }
        $data['files'] = $files;
        return $data;
    }
}
