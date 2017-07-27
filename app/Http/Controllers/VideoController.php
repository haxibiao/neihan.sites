<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        $videos = Video::paginate(10);
        return view('video.index')->withVideos($videos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('video.show')->withVideo($video);
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

        //保存mp4
        $file      = $request->file('video');
        $extension = $file->getClientOriginalExtension();
        $filename  = $video->id . '.' . $extension;
        $path      = '/storage/video/' . $filename;
        $local_dir = public_path('/storage/video/');
        $video_path = $local_dir . $filename;
        $file->move($local_dir, $filename);

        //截取图片
        $video->cover = '/storage/video/thumbnail_' . $video->id . '.jpg';
        $cover        = public_path($video->cover);
        $second       = rand(1, 10); //随机截取１－１０秒的屏幕
        $cmd          = "ffmpeg -i $video_path -deinterlace -an -s 300x200 -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover 2>&1";
        $do           = `$cmd`;

        $video->update($request->all());

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
            //记载当前用户已上传，未使用的
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
            $extension = $file->getClientOriginalExtension();
            $filename  = $video_index . '.' . $extension;
            $path      = '/storage/video/' . $filename;
            $local_dir = public_path('/storage/video/');
            $full_path = $local_dir . $filename;

            //save video file
            $file->move($local_dir, $filename);

            //save video item
            $video          = new Video();
            $video->user_id = Auth::user()->id;
            $video->path    = $path;
            $video->save();

            //get duration
            $video_path = $full_path;
            $cmd        = "ffmpeg -i $video_path 2>&1";
            $second     = 1;
            // if (preg_match('/Duration: ((\d+):(\d+):(\d+))/s', `$cmd`, $time)) {
            //     $total = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
            //     $video->duration = $total;
            //     $second = rand(1, ($total - 1));
            // }

            //make thumbnail
            $video->cover = '/storage/video/thumbnail_' . $video->id . '.jpg';
            $cover        = public_path($video->cover);
            $cmd          = "ffmpeg -i $video_path -deinterlace -an -s 300x200 -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover 2>&1";
            $do           = `$cmd`;
            $video->save();

            $files[] = [
                'url'          => base_uri() . $video->path,
                'thumbnailUrl' => base_uri() . $video->cover,
                'name'         => $path,
                "type"         => 'video/mp4',
                "size"         => filesize($full_path),
                'deleteUrl'    => url('/video?d=' . $video->id),
                "deleteType"   => "GET",
            ];
        }

        $data['files'] = $files;

        return $data;
    }
}
