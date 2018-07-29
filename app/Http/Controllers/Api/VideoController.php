<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Helpers\QcloudUtils;
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
                ['type', '=', 'video']]);
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
        //TODO 清除关系 分类关系 冗余的统计信息  评论信息 点赞信息 喜欢的信息 收藏的信息

        return 1;
    }

    /**
     * @Desc     上传视频的接口
     * @Author   czg
     * @DateTime 2018-06-28
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', '256M');

        //如果是通过表单上传文件
        $file = $request->video;
        if ($file) {
            $hash  = md5_file($file->path());
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            if ($video->id) {
                // abort(505,"相同视频已存在");
            }
            $video->title = $file->getClientOriginalName();
            $video->save();
            //虽然是简单的上传文件但是文章关系还是存了下来
            $article            = new Article();
            $article->user_id   = getUserId();
            $article->video_url = $video->getPath();
            $article->type      = 'video';
            $article->image_url = '/images/uploadImage.jpg'; //默认图
            $article->video_id  = $video->id;
            $article->title     = $file->getClientOriginalName(); //新上传的文件直接使用文件的原始名称
            $article->save();

            //保存视频,简单保存视频不需要更新文章的发布状态
            $video->saveFile($file, false);
            return [
                'video_id'  => $video->id,
                'video_url' => $video->url(),
                'image_url' => url('/images/uploadImage.jpg'),
            ];
        }

        //腾讯云上传成功后的回调
        if ($request->from == 'qcvod') {
            $video = Video::firstOrNew([
                'qcvod_fileid' => $request->fileId,
            ]);
            $video->user_id = getUserId();
            $video->path    = get_secure_url($request->videoUrl);
            $video->title   = $request->videoName;
            $video->save();

            //调用 vod api , 开始转码，水印，生成截图（非300*200，需要后面UI处理显示效果） ...
            QcloudUtils::simpleProcessFile($request->fileId);

            //TODO:: 保存主封面 save video->cover, then sync to article->image_url
            //定时任务，pull_events,还没有根据fileid getVideoInfo直接拉取方便，延迟job还未必可靠，schedule:run是个不错的选择

            return $video;
        }

        return "没有腾讯云视频id，也没有真实上传的视频文件";
    }

    public function show($id)
    {
        $video = Video::with('user')->with('article.category')->findOrFail($id);
        return $video;
    }

    public function getLatestVideo(Request $request)
    {
        $data = Article::where('type', 'video')->whereStatus(1)->orderByDesc('updated_at');
        if ($request->get('stick')) {
            $videos   = get_stick_videos('', true);
            $videoIds = [];
            foreach ($videos as $video) {
                $videoIds[] = $video->id;
            }
            $data = Article::whereIn('video_id', $videoIds);
        }
        $data = $data->paginate(9);
        foreach ($data as $article) {
            $article->fillForJs();
        }
        return $data;
    }
}
