<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $data  = [];
        $query = Article::with('user')
            ->with('category')
            ->orderBy('id', 'desc')
            ->where([
                ['status', '>', 0],
                ['type', '=', 'video'],
            ]);
        if (Auth::check()) {
            $query = $query->where('user_id', Auth::user()->id);
        }
        $videos = $query->take(12)->get();
        foreach ($videos as $video) {
            $extension = pathinfo($video->path, PATHINFO_EXTENSION);
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
        return $data;
    }
    /**
     * @Desc     删除视频，硬删除
     * @Author   czg
     * @DateTime 2018-06-28
     * @param    [type]     $id [description]
     * @return   [type]         [description]
     */
    public function destroy($id)
    {

        $article = Article::findOrFail($id);
        $video   = $article->video;
        //上传文件不是通过CDN上传，需要清除磁盘上的文件
        if (!starts_with($video->path, 'http')) {
            $video_path = public_path($video->path);
            if (file_exists($video_path)) {
                unlink($video_path);
            }
        }
        $video->delete();
        $article->delete();

        return 1;
    }

    public function store(Request $request)
    {
        ini_set('memory_limit', '256M');

        //如果是通过表单上传文件
        $file = $request->video;
        if ($file) {
            //TODO 限制视频大小超过10M
            return $this->storeWithFile($file);
        }

        //腾讯云 视频云 qcvod 回调接口，目前已弃用
        // if ($request->from == 'qcvod') {
        //     $video = Video::firstOrNew([
        //         'qcvod_fileid' => $request->fileId,
        //     ]);
        //     $video->user_id = $request->user()->id;
        //     $video->path    = ssl_url($request->videoUrl); //保存https的video cdn 地址
        //     $video->title   = $request->videoName;
        //     $video->save();
        //     return $video;
        // }

        return "没有腾讯云视频id，也没有真实上传的视频文件";
    }

    public function storeWithFile($file)
    {
        $hash  = md5_file($file->path());
        $video = Video::firstOrNew([
            'hash' => $hash,
        ]);
        if ($video->id) {
            return [
                'video_id'  => $video->id,
                'video_url' => $video->url,
                'image_url' => $video->cover, //TODO: 检查 video cover 属性
            ];
        }
        $video->title = $file->getClientOriginalName();
        $video->save();

        //保存视频,简单保存视频不需要更新文章的发布状态
        $video->saveFile($file, false);

        if ($video->path) {
            //简单的上传文件成功后，保存个草稿文章对应，方便后续重新发布此草稿
            $article            = new Article();
            $article->video_id  = $video->id;
            $article->status    = 0; //草稿
            $article->user_id   = getUserId();
            $article->type      = 'video';
            $article->image_url = null; //默认封面， //TODO: 字段应该改名为 cover
            $article->title     = "正在输入...";
            $article->save();
        }

        return [
            'video_id'  => $video->id,
            'video_url' => $video->url,
            'image_url' => $video->cover,
        ];
    }

    public function show($id)
    {
        $video = Video::with('user')->with('article.category')->findOrFail($id);
        return $video;
    }

    public function getLatestVideo(Request $request)
    {
        $videos   = get_stick_videos('', true);
        $videoIds = [];
        foreach ($videos as $video) {
            $videoIds[] = $video->article->id;
        }
        if ($request->get('stick')) {
            $data = Article::whereIn('id', $videoIds);
        } else {
            $data = Article::where('type', 'video')->whereStatus(1)->orderByDesc('updated_at');
            if (!empty($videoIds)) {
                $data = Article::where('type', 'video')->whereStatus(1)->whereNotIn('id', $videoIds)
                    ->orderByDesc('updated_at');
            }
        }
        $data = $data->paginate(9);
        foreach ($data as $article) {
            $article->fillForJs();
        }
        return $data;
    }

    public function covers($id)
    {
        $video = Video::with('article')->findOrFail($id);

        if (empty($video->article)) {
            abort(404, '视频对应的文章不见了');
        }

        //如果还没有封面，可以尝试sync一下vod结果了
        if (empty($video->jsonData('covers'))) {
            $video->syncVodProcessResult();
        }
        return $video->covers;
    }
}
