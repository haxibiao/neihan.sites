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

        $images = image::with('user')->orderBy('updated_at', 'desc')->paginate(10);
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
            if (file_exists(public_path($path))) {
                unlink(public_path($path));
            }
            $data[$path] = true;
            $path_small  = $image->path_small;
            if (file_exists(public_path($path_small))) {
                unlink(public_path($path_small));
            }
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
        $image_index = get_cached_index(Image::max('id'));
        $files       = [];
        foreach ($images as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename  = $file->getClientOriginalName() . '-' . $image_index . '.' . $extension;
            $path      = '/img/' . $filename;

            $image = new Image();
            // $image->title   = $file->getClientOriginalName();
            $image->user_id = Auth::user()->id;
            $image->path    = $path;

            $local_dir = public_path('img/');
            $full_path = $local_dir . $filename;

            $img = \ImageMaker::make($file->path());
            if ($extension != 'gif') {
                $big_width = $img->width() > 900 ? 900 : $img->width();
                $img->resize($big_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                //save big
                $img->save($full_path);

                $image->width  = $img->width();
                $image->height = $img->height();
            } else {
                $file->move($local_dir, $filename);
            }

            //save top
            if ($extension != 'gif') {
                if ($img->width() >= 750) {
                    $img->crop(750, 400);
                    $img->save($full_path . '.top.' . $extension);
                    $image->path_top = $path . '.top.' . $extension;
                }
            } else {
                if ($img->width() >= 750) {
                    $image->path_top = $path;
                }
            }

            //save small
            if ($img->height() > $img->width()) {
                $img->resize(320, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if($img->width() > 240) {
                    $img->crop(320,240);
                }
            } else {
                $img->resize(null, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if($img->height() > 320) {
                    $img->crop(320,240);
                }
            }
            $img->crop(320, 240);
            $image->path_small = $path . '.small.' . $extension;
            $img->save($full_path . '.small.' . $extension);
            $image->save();
            $files[] = [
                'url'          => base_uri() . $image->path,
                'thumbnailUrl' => base_uri() . $image->path,
                'name'         => $path,
                'id'           => $image->id,
                "type"         => str_replace("jpg", "jpeg", "image/" . $extension),
                "size"         => filesize($full_path),
                'deleteUrl'    => url('/image?d=' . $image->id),
                "deleteType"   => "GET",
            ];
        }

        $data['files'] = $files;

        return $data;
    }
}
