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
            $path_small  = $image->path_small;
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
        $images      = $request->file('files');
        $files       = [];
        foreach ($images as $file) {
            $image = new Image();
            $image->save();
            $extension = $file->getClientOriginalExtension();
            $filename  = $image->id . '.' . $extension;
            $image->path    = '/img/' . $filename;
            $image->user_id = Auth::user()->id;
            

            $local_dir = public_path('img/');
            if (!is_dir($local_dir)) {
                mkdir($local_dir, 0777, 1);
            }

            $img = \ImageMaker::make($file->path());
            if ($extension != 'gif') {
                $big_width = $img->width() > 900 ? 900 : $img->width();
                $img->resize($big_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                //save big
                $img->save(public_path($image->path));

                $image->width  = $img->width();
                $image->height = $img->height();
            } else {
                $file->move($local_dir, $filename);
            }

            //save top
            if ($extension != 'gif') {
                if ($img->width() >= 750) {
                    $img->crop(750, 400);
                    $image->path_top = '/img/' .$image->id . '.top.' . $extension;
                    $img->save(public_path($image->path_top));
                }
            } else {
                if ($img->width() >= 750) {
                    $image->path_top = $image->path;
                }
            }

            //save small
            if ($img->width() / $img->height() < 1.5) {
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img->resize(null, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $img->crop(300, 200);
            $image->path_small = '/img/' . $image->id . '.small.' . $extension;
            $img->save(public_path($image->path_small));
            $image->save();
            $files[] = [
                'url'          => base_uri() . $image->path,
                'thumbnailUrl' => base_uri() . $image->path_small,
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
