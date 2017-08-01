<?php

namespace App\Http\Controllers;

use App\Article;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->jsonIndex($request);
        }

        $videos = Video::with('user')->orderBy('updated_at', 'desc')->paginate(10);
        return view('video.index')->withVideos($videos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('video.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', '256M');

        if ($request->ajax()) {
            return $this->jsonStore($request);
        }

        $video = new Video($request->all());

        $file = $request->file('video');
        if ($file) {
            $extension    = $file->getClientOriginalExtension();
            $origin_name  = $file->getClientOriginalName();
            $video->title = str_replace('.mp4', '', $origin_name);
            $video_index  = get_cached_index(Video::max('id'), 'video');
            $filename     = $origin_name . '-' . $video_index . '.' . $extension;
            $path         = '/storage/video/' . $filename;
            $video->path  = $path;
            $file->move(public_path('/storage/video/'), $filename);
        } else {
            if (empty($video->title)) {
                //自动获取视频文件做标题
                $filename     = pathinfo(parse_url($video->path)['path'])['filename'];
                $filename     = str_replace('.flv', '', $filename);
                $video->title = $filename;
            }
        }
        $video->save();

        //截取图片
        $video_path   = starts_with($video->path, 'http') ? $video->path : public_path($video->path);
        $video->cover = '/storage/video/thumbnail_' . $video->id . '.jpg';
        $cover        = public_path($video->cover);
        $this->make_cover($video_path, $cover);

        $video->save();
        return redirect()->to('/video');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video              = Video::with('user')->findOrFail($id);
        $data['json_lists'] = $this->get_json_lists($video);
        return view('video.show')->withVideo($video)->withData($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('video.edit')->withVideo($video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->update($request->all());

        $video_path = $video->path;
        if (!starts_with($video_path, 'http')) {
            //保存mp4
            $file = $request->file('video');
            if ($file) {
                $extension    = $file->getClientOriginalExtension();
                $origin_name  = $file->getClientOriginalName();
                $video->title = str_replace('.mp4', '', $origin_name);
                $filename     = $origin_name . '-' . $video->id . '.' . $extension;
                $path         = '/storage/video/' . $filename;
                $video->path  = $path;
                $file->move(public_path('/storage/video/'), $filename);
            }
            $video_path = public_path($video->path);
        } else {
            if (empty($video->title)) {
                //自动获取视频文件做标题
                $filename     = pathinfo(parse_url($video->path)['path'])['filename'];
                $filename     = str_replace('.mp4', '', $filename);
                $video->title = $filename;
            }
        }

        //截取图片
        $video->cover = '/storage/video/thumbnail_' . $video->id . '.jpg';
        $cover        = public_path($video->cover);
        $this->make_cover($video_path, $cover);

        $video->save();

        return redirect()->to('/video');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function jsonIndex($request)
    {
        $data      = [];
        $delete_id = $request->get('d');
        if (!empty($delete_id)) {
            //删除
            $video = Video::find($delete_id);
            if ($video) {
                $video->status = -1;
                $video->save();
            }
            $path = $video->path;
            if (file_exists(public_path($path))) {
                unlink(public_path($path));
            }
            $data[$path] = true;
            $cover       = $video->cover;
            if (file_exists(public_path($cover))) {
                unlink(public_path($cover));
            }
            $data[$cover] = true;
        } else {
            //加载当前用户已上传，未使用的
            $query = Video::where('status', '>=', 0)->where('count', 0);
            if (Auth::check()) {
                $user_id = Auth::check() ? Auth::user()->id : 0;
                $query   = $query->where('user_id', $user_id);
            }
            $videos = $query->get();
            foreach ($videos as $video) {
                $extension = pathinfo(public_path($video->path), PATHINFO_EXTENSION);
                $filename  = pathinfo($video->path)['basename'];
                $file      = [
                    'url'          => base_uri() . $video->path,
                    'thumbnailUrl' => base_uri() . $video->cover,
                    'name'         => $filename,
                    'id'           => $video->id,
                    "type"         => str_replace("jpg", "jpeg", "video/" . $extension),
                    "size"         => 0,
                    'deleteUrl'    => url('/video?d=' . $video->id),
                    "deleteType"   => "GET",
                ];
                $data['files'][] = $file;
            }
        }
        return $data;
    }

    public function jsonStore($request)
    {
        $videos      = $request->file('files');
        $video_index = get_cached_index(Video::max('id'), 'video');
        $video_dir   = public_path('/storage/video/');
        if (!is_dir($video_dir)) {
            mkdir($video_dir, 0777, 1);
        }

        $files = [];
        foreach ($videos as $file) {
            $extension   = $file->getClientOriginalExtension();
            $origin_name = $file->getClientOriginalName();
            $filename    = $origin_name . '-' . $video_index . '.' . $extension;
            $path        = '/storage/video/' . $filename;
            $local_dir   = public_path('/storage/video/');
            $size        = filesize($file->path());

            //save video item
            $hash  = md5_file($file->path());
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            if (!$video->id) {
                $video->title   = $origin_name;
                $video->user_id = Auth::user()->id;
                $video->path    = $path;
                $video->hash    = $hash;
                $video->save();

                //save video file
                $file->move($local_dir, $filename);

                //get duration
                $video_path = public_path($path);
                // $cmd        = "ffmpeg -i $video_path 2>&1";
                // if (preg_match('/Duration: ((\d+):(\d+):(\d+))/s', `$cmd`, $time)) {
                //     $total = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
                //     $video->duration = $total;
                //     $second = rand(1, ($total - 1));
                // }

                //make thumbnail
                $video->cover = '/storage/video/thumbnail_' . $video->id . '.jpg';
                $cover        = public_path($video->cover);
                $this->make_cover($video_path, $cover);
                $video->save();
            }

            $files[] = [
                'url'          => base_uri() . $video->path,
                'thumbnailUrl' => base_uri() . $video->cover,
                'name'         => $filename,
                'id'           => $video->id,
                "type"         => 'video/mp4',
                "size"         => $size,
                'deleteUrl'    => url('/video?d=' . $video->id),
                "deleteType"   => "GET",
            ];
        }

        $data['files'] = $files;

        return $data;
    }

    public function make_cover($video_path, $cover)
    {
        if (starts_with($video_path, 'http')) {
            $second = rand(14, 18);
            $cmd    = "ffmpeg -i $video_path -deinterlace -an -s 300x200 -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover 2>&1";
            $do     = `$cmd`;
        } else {
            $cmd = "ffmpeg -i $video_path -frames:v 1 -s 300x200 $cover 2>&1";
            $do  = `$cmd`;
        }
    }

    public function get_json_lists($video)
    {
        $lists     = json_decode($video->json, true);
        $lists_new = [];
        if (is_array($lists)) {
            foreach ($lists as $key => $data) {
                if (!is_array($data)) {
                    $data = [];
                }
                $items = [];
                if (!empty($data['aids']) && is_array($data['aids'])) {
                    foreach ($data['aids'] as $aid) {
                        $article = Article::find($aid);
                        if ($article) {
                            $items[] = [
                                'id'        => $article->id,
                                'title'     => $article->title,
                                'image_url' => get_img($article->image_url),
                            ];
                        }
                    }
                }
                if (!empty($items)) {
                    $data['items']   = $items;
                    $lists_new[$key] = $data;
                }
            }
        }
        return $lists_new;
    }
}
