<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                'thumbnailUrl' => base_uri() . $video->coverUrl,
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
        if ($file = $request->video) {
            //TODO 限制视频大小超过10M
            $hash  = md5_file($file->path());
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            if ($video->id) {
                //TODO: 后面需要跳过重复上传，暂时为了测试方便
                // return $video->fillForJs();
            }
            $video->saveFile($file);

            return $video->fillForJs();
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

    public function cosHookVideo(Request $request)
    {
        $inputs = $request->input();

        $playUrl  = array_get($inputs, 'playurl');
        $cosAppId = env('COS_APP_ID');
        $bucket   = env('COS_BUCKET');

        Log::info($inputs);

        $bucketPrefix = sprintf('/%s/%s/', $cosAppId, $bucket);

        if ($playUrl) {
                //高清视频
                if( Str::endsWith($playUrl,'f30.mp4') ){
                    $videoPath    = str_replace([$bucketPrefix, '.f30.mp4'], '', $playUrl);
                    $video = Video::where('path','like', $videoPath . '%')->first();
                    $path = str_replace($bucketPrefix, '', $playUrl);
                    if($video){
                        $video->setJsonData('transcode_hd_mp4',$path);
                        $video->path = $path;//默认展示高清视频
                        $video->save();
                    }
                //标清视频
                } else if( Str::endsWith($playUrl,'f20.mp4') ){
                    $videoPath    = str_replace([$bucketPrefix, '.f20.mp4'], '', $playUrl);
                    $video = Video::where('path', 'like', $videoPath . '%')->first();
                    if($video){
                        $path = str_replace($bucketPrefix, '', $playUrl);
                        $video->setJsonData('transcode_sd_mp4',$path);
                    }
                //低清视频
                } else if( Str::endsWith($playUrl,'f10.mp4') ){
                    $videoPath    = str_replace([$bucketPrefix, '.f10.mp4'], '', $playUrl);
                    $video = Video::where('path', 'like', $videoPath . '%')->first();
                    if($video){
                        $path = str_replace($bucketPrefix, '', $playUrl);
                        $video->setJsonData('transcode_ld_mp4',$path);
                    }
                }
            }
    }
}
