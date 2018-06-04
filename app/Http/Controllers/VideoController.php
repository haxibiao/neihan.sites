<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Jobs\videoCapture;
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

        $videos = Video::with('user')->with('category')->orderBy('id', 'desc')->paginate(10);
        return view('video.index')->withVideos($videos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['video_categories'] = Category::where('type', 'video')->pluck('name', 'id');
        return view('video.create')->withData($data);
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

        //处理视频与分类的关系
        $this->process_category($video);

        $file = $request->file('video');
        if ($file) {
            $extension   = $file->getClientOriginalExtension();
            $origin_name = $file->getClientOriginalName();
            $filename    = str_replace('.mp4', '', $origin_name);
            if (empty($video->title)) {
                $video->title = $filename;
            }

            $video_index = get_cached_index(Video::max('id'), 'video');
            $filename    = $video_index . '.' . $extension;
            $path        = '/storage/video/' . $filename;
            $video->path = $path;
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

        videoCapture::dispatch($video_path, $cover, $video->id);

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
        $video = Video::with('user')->with('articles')->with('category')->findOrFail($id);
        //增加点击量
        $video->increment('hits', 1);

        $data['json_lists'] = $this->get_json_lists($video);
        // dd($data['json_lists']);

        //get some related videos ...
        $videos               = Video::orderBy('id', 'desc')->skip(rand(0, Video::count() - 8))->take(4)->get();
        $video_category_query = $video->category;
        if ($video_category_query && $video_category_query->videos()->count() >= 4) {
            $videos = $video->category->videos()->take(4)->get();
        }

        //dd($videos);
        $data['related'] = $videos;

        $current_catagory = $video_category_query->first();
        return view('video.show')
            ->withVideoDesc($video)
            ->withCategory($current_catagory)
            ->withData($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video                    = Video::findOrFail($id);
        $data['video_categories'] = Category::where('type', 'video')->pluck('name', 'id');
        $data['thumbnail']        = $video->covers();

        return view('video.edit')->withVideo($video)->withData($data);
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
        //维护分类关系
        $this->process_category($video);

        if (!empty($request->thumbnail)) {
            $result = copy(public_path($request->thumbnail), public_path($video->cover));
        }

        $video_path = $video->path;
        if (!starts_with($video_path, 'http')) {
            //保存mp4
            $file = $request->file('video');
            if ($file) {
                $extension   = $file->getClientOriginalExtension();
                $origin_name = $file->getClientOriginalName();
                $filename    = str_replace('.mp4', '', $origin_name);
                if (empty($video->title)) {
                    $video->title = $filename;
                }

                $filename    = $video->id . '.' . $extension;
                $path        = '/storage/video/' . $filename;
                $video->path = $path;
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
        $video = Video::with('articles')->findOrFail($id);
        if ($video->articles->isEmpty()) {
            if (!starts_with($video->path, 'http')) {
                $video_path = public_path($video->path);
                if (file_exists($video_path)) {
                    unlink($video_path);
                }
            }
        } else {
            return '视频还在被文章引用...　无法删除...';
        }

        $video->delete();
        return redirect()->to('/video');
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
            $query = Video::where('status', '>=', 0)->where('count', 0)->orderBy('id', 'desc');
            if (Auth::check()) {
                $query = $query->where('user_id', Auth::user()->id);
            }
            $videos = $query->take(2)->get();
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
            $filename    = $video_index . '.' . $extension;
            $path        = '/storage/video/' . $filename;
            $local_dir   = public_path('/storage/video/');
            $size        = filesize($file->path());

            //save video item
            $hash  = md5_file($file->path());
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            if (!$video->id) {
                $video->title       = $origin_name;
                $video->user_id     = Auth::user()->id;
                $video->path        = $path;
                $video->hash        = $hash;
                $video->category_id = Category::where('name', '有意思')->where('type', 'video')->first()->id;
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
                videoCapture::dispatch($video_path, $cover);
                $video->save();
            }

            $files[] = [
                'url'          => $video->path,
                'thumbnailUrl' => $video->cover,
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
        $covers           = [];
        $cmd_get_duration = 'ffprobe -i ' . $video_path . ' -show_entries format=duration -v quiet -of csv="p=0" 2>&1';
        $duration         = `$cmd_get_duration`;
        $duration         = intval($duration);
        if ($duration > 15) {
            //take 8 covers jpg file, return first ...
            for ($i = 1; $i <= 8; $i++) {
                $seconds   = intval($duration * $i / 8);
                $cover_i   = $cover . ".$i.jpg";
                $cmd       = "ffmpeg -i $video_path -deinterlace -an -s 300x200 -ss $seconds -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover_i 2>&1";
                $exit_code = exec($cmd);
                if ($exit_code == 0) {
                    $covers[] = $cover_i;
                }
            }

            if (count($covers)) {
                //copy first screen as default cover..
                copy($covers[0], $cover);
            }

        }
        return $covers;
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
                                'image_url' => $article->image_url,
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

    //处理视频与分类的关系
    public function process_category($video)
    {
        $old_categories   = $video->categories;
        $new_categories   = json_decode(request('categories'));
        $new_category_ids = [];
        //选取第一个分类做视频的主分类
        if (!empty($new_categories)) {
            $video->category_id = $new_categories[0]->id;
            $video->save();
            foreach ($new_categories as $cate) {
                $new_category_ids[] = $cate->id;
            }
        }
        //同步分类关系,以最后一次选取的为准
        $video->categories()->sync($new_category_ids);
        //更新分类下的视频数量
        if (is_array($new_categories)) {
            foreach ($new_categories as $category) {
                //更新新分类文章数
                if ($category = Category::find($category->id)) {
                    $category->count_videos = $category->videos()->count();
                    $category->save();
                }
            }
        }
        //更新旧分类文章数
        foreach ($old_categories as $category) {
            $category->count_videos = $category->videos()->count();
            $category->save();
        }
    }
}
