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
            $path = '/img/' . $delete_filename;
            if (file_exists(public_path($path))) {
                unlink(public_path($path));
            }
            $data[$path] = true;
            $path_small  = $path . '.small.jpg';
            if (file_exists(public_path($path_small))) {
                unlink(public_path($path_small));
            }
            $image = Image::find(str_replace('.jpg', '', $delete_filename));
            if ($image) {
                $image->delete();
            }
            $data[$path_small] = true;
        } else {
            //load exist ...
            $images  = Image::where('count', 0)->get();
            $http    = $request->secure() ? 'https://' : 'http://';
            $baseUri = $http . $request->server('HTTP_HOST');
            foreach ($images as $image) {
                $filename = pathinfo($image->path)['filename'];
                $file     = [
                    'url'          => $baseUri . $image->path,
                    'thumbnailUrl' => $baseUri . $image->path,
                    'name'         => $filename,
                    "type"         => "image/jpeg",
                    "size"         => 0,
                    'deleteUrl'    => url('/image?delete_filename=' . $filename),
                    "deleteType"   => "GET",
                ];
                $data['files'][] = $file;
            }
        }
        return $data;
    }

    public function create()
    {
        return 'not imple...';
    }

    public function store(Request $request)
    {
        $images      = $request->file('image');
        $image_index = get_image_index(Image::max('id'));
        $files = [];
        foreach ($images as $file) {
            $filename   = $image_index . '.jpg';
            $path       = '/img/' . $filename;
            $local_path = public_path('img/');
            $file->move($local_path, $filename);

            $image       = new Image();
            $image->path = $path;
            $full_path   = $local_path . $filename;
            $img         = \ImageMaker::make($full_path);
            $img->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path . '.small.jpg');
            $image->path_small = $path . '.small.jpg';
            $image->save();
            $http    = $request->secure() ? 'https://' : 'http://';
            $baseUri = $http . $request->server('HTTP_HOST');
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
