<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $data            = [];
        $delete_filename = $request->get('delete_filename');
        if (!empty($delete_filename)) {
            $path = 'img/' . $delete_filename;
            if (file_exists(public_path($path))) {
                unlink($path);
            }
            $data[$path] = true;
            $path_small  = $path . '.small.jpg';
            if (file_exists(public_path($path_small))) {
                unlink($path_small);
            }
            $data[$path_small] = true;
        }
        return $data;
    }

    public function create()
    {
        return 'not imple...';
    }

    public function store(Request $request)
    {
        $images = $request->file('image');
        $index  = 1;
        $files  = [];
        foreach ($images as $file) {
            $filename = time() . '.' . $index . '.jpg';
            $index++;
            $path       = 'img/' . $filename;
            $local_path = public_path('img/');
            $file->move($local_path, $filename);

            $image       = new Image();
            $image->path = $path;
            $full_path   = $local_path . $filename;
            $img         = \ImageMaker::make($full_path);
            $img->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path . '.small.jpg');
            $image->path_small = $path . '.small.jpg';
            $image->save();
            $http    = $request->secure() ? 'https://' : 'http://';
            $baseUri = $http . $request->server('HTTP_HOST') . '/';
            $files[] = [
                'url'          => $baseUri . $image->path,
                'thumbnailUrl' => $baseUri . $image->path,
                'name'         => $filename,
                "type"         => "image/jpeg",
                "size"         => filesize($file->path()),
                'deleteUrl'    => url('/image?delete_filename=' . $filename),
                "deleteType"   => "GET",
            ];
        }

        $data['files'] = $files;

        return $data;
    }

    public function show($id)
    {
        return 'not imple...';
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
        return 'deleting ... ' . $deleteUrl;
    }
}

// {
//     "files": [{
//         "url": "https://jquery-file-upload.appspot.com/image/jpeg/1549240106/下载.jpg",
//         "thumbnailUrl": "https://jquery-file-upload.appspot.com/image/jpeg/1549240106/下载.jpg.80x80.jpg",
//         "name": "下载.jpg",
//         "type": "image/jpeg",
//         "size": 8657,
//         "deleteUrl": "https://jquery-file-upload.appspot.com/image/jpeg/1549240106/下载.jpg",
//         "deleteType": "DELETE"
//     }]
// }
