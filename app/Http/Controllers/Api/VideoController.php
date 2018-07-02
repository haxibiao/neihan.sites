<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Video;
use App\Article;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $data      = [];
        $query = Article::with('user')
            ->with('category')
            ->orderBy('id', 'desc')
            ->where([
                ['status', '>', 0],
                ['type'  , '=','video'],]);
        if (Auth::check()) {
            $query = $query->where('user_id', Auth::user()->id);
        }
        $videos = $query->take(12)->get();
        foreach ($videos as $video) {
            $extension = pathinfo(public_path($video->video_url), PATHINFO_EXTENSION);
            $filename  = pathinfo($video->video_url)['basename'];
            $file      = [
                'url'          => base_uri() . $video->video_url,
                'thumbnailUrl' => base_uri() . $video->image_url,
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
        if ( !starts_with( $video->path, 'http' ) ) {
            $video_path = public_path( $video->path );
            if ( file_exists($video_path) ) {
                unlink($video_path);
            }
        }
        $video  ->delete();
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
                abort(505,"相同视频已存在");
            }
            // $video->user_id = $request->user()->id;
            
            //保存视频
            $video->saveFile($file);

            return [
                'video_id' => $video->id, 
                'video_url' => $video->url()
            ];
        }

        abort(501, '没收到上传的文件');
    }

    public function show($id)
    {
        $video                 = Video::with('user')->with('category')->findOrFail($id);
        $video->path           = get_full_url($video->path);
        $video->cover          = get_full_url($video->cover);
        $video->category->logo = get_full_url($video->category->logo);
        $video->pubtime        = diffForHumansCN($video->created_at);
        $controller            = new \App\Http\Controllers\VideoController();
        $video->connected      = $controller->get_json_lists($video);
        return $video;
    }

    public function saveRelation(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $data  = json_decode($video->json);
        if (empty($data)) {
            $data = [];
        }
        $data[]      = $request->all();
        $video->json = json_encode($data);
        $video->save();

        return $video;
    }

    public function getAllRelations(Request $request, $id)
    {
        $video     = Video::findOrFail($id);
        $contoller = new \App\Http\Controllers\VideoController();
        return $contoller->get_json_lists($video);
    }

    public function deleteRelation(Request $request, $id, $key)
    {
        $video = Video::findOrFail($id);
        $data  = json_decode($video->json);
        if (empty($data)) {
            $data = [];
        }
        $data_new = [];
        foreach ($data as $k => $list) {
            if ($k == $key) {
                continue;
            }
            $data_new[] = $list;
        }

        $video->json = json_encode($data_new);
        $video->save();

        return $data_new;
    }

    public function getRelation($id, $key)
    {
        $video = Video::findOrFail($id);
        $json  = json_decode($video->json, true);
        if (array_key_exists($key, $json)) {
            $data = $json[$key];
            if (empty($data['type']) || $data['type'] == 'single_list') {
                $items = [];
                if (is_array($data['aids'])) {
                    foreach ($data['aids'] as $aid) {
                        $video = Video::find($aid);
                        if ($video) {
                            $items[] = [
                                'id'        => $video->id,
                                'title'     => $video->title,
                                'image_url' => get_full_url($video->cover),
                            ];
                        }
                    }
                }
                $data['items'] = $items;
            }

            return $data;
        }
        return null;
    }
}
