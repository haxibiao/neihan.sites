<?php

namespace App\Http\Controllers;

use App\Article;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
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
                    'url'          => $image->path,
                    'thumbnailUrl' => $image->path_small,
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
        $user_id = 1;
        $images  = $request->file('files');
        if (!is_array($images)) {
            $images = [$request->photo];
        }
        $files = [];
        foreach ($images as $file) {
            $image = new Image();
            $image->save();
            $extension        = $file->getClientOriginalExtension();
            $filename         = $image->id . '.' . $extension;
            $image->extension = $extension;
            $image->path      = '/storage/img/' . $filename;
            $image->user_id   = Auth::check() ? Auth::user()->id : $user_id;

            $local_dir = public_path('/storage/img/');
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
                if ($img->width() >= 760) {
                    $img->crop(760, 327);
                    $image->path_top = '/storage/img/' . $image->id . '.top.' . $extension;
                    $img->save(public_path($image->path_top));
                }
            } else {
                if ($img->width() >= 760) {
                    $image->path_top = $image->path;
                }
            }

            //save small
            if ($img->width() / $img->height() < 1.5) {
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img->resize(null, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $img->crop(300, 240);
            $image->path_small = '/storage/img/' . $image->id . '.small.' . $extension;
            $img->save(public_path($image->path_small));
            $image->save();
            $files[] = [
                'url'          => $image->path,
                'thumbnailUrl' => $image->path_small,
                'name'         => $image->path,
                'id'           => $image->id,
                "type"         => str_replace("jpg", "jpeg", "image/" . $extension),
                "size"         => filesize(public_path($image->path)),
                'deleteUrl'    => url('/image?d=' . $image->id),
                "deleteType"   => "GET",
            ];
        }

        $data['files'] = $files;

        //给simditor编辑器返回上传图片结果...
        if ($request->get('from') == 'simditor') {
            // $json = "{ success: true, msg:'图片上传成功', file_path: '" . $path_big . "' }";
            $json            = (object) [];
            $json->success   = true;
            $json->msg       = '图片上传成功';
            $json->file_path = $image->path;
            return json_encode($json);
        }

        //response dopm
        if($request->get('from') =='question'){
            return $image->path;
        }

        return $data;
    }

    public function poster()
    {
        $data             = [];
        $data['articles'] = Article::where('is_top', 1)->take(8)->get();
        return view('user.poster')
            ->withData($data);
    }

    //TODO ::经典手动创建分页代码.
    public function poster_all()
    {
        ini_set('memory_limit', '-1');

        $articles = Article::orderBy('id')->get();
        foreach ($articles as $article) {
            if ($article->image_url) {
                $image = Image::where('path', $article->image_url)->first();
                if ($image && !empty($image->path_top)) {
                    $top_path = public_path($image->path_top);
                    if (file_exists($top_path) && $image->width >=760) {
                            $data['articles'][] = $article;
                    }
                }
            }
        }

        $data['articles'] = array_reverse($data['articles']);

        $total   = count($data['articles']);
        $perPage = 10;
        if (request()->has('page')) {
            $current_page = request()->get('page');
            $current_page = $current_page <= 0 ? 1 : $current_page;
        } else {
            $current_page = 1;
        }
        $item             = array_slice($data['articles'], ($current_page - 1) * $perPage, $perPage);
        $data['articles'] = new LengthAwarePaginator($item, $total, $perPage, null, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        $data['page'] = 1;
        return view('user.poster')
            ->withData($data);
    }
}
