<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpiderController extends Controller
{
    public function importDouyinSpider(Request $request){
        try{
            $video_url      = $request->video_url;
            $account        = $request->account;
            $metaInfo       = $request->description;

            $description = Str::replaceFirst('#在抖音，记录美好生活#','',$metaInfo);
            if(Str::contains($description,'#')){
                $description = Str::before($description,'#');
            } else {
                $description = Str::before($description,'http');
            }


            $user = User::where('account',$account)
                ->orWhere('email',$account)
                ->firstOrFail();
            Auth::login($user);
            $hash  = md5_file($video_url);
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            $video->setJsonData('metaInfo', $metaInfo);
            $video->user_id = $user->id;
            $video->title = $description;
            $video->save();//不触发事件通知


            //本地存一份用于截图
            $cosPath     = 'video/' . $video->id . '.mp4';
            $video->path  = $cosPath;
            Storage::disk('public')->put($cosPath, @file_get_contents($video_url));
            $video->disk = 'local'; //先标记为成功保存到本地
            $video->save();

            //将视频上传到cos
            $cosDisk = \Storage::cloud();
            $cosDisk->put($cosPath, \Storage::disk('public')->get($cosPath));
            $video->disk = 'cos';
            $video->save();

            //TODO 分类关系
            $category = Category::firstOrNew([
                'name' => '抖音合集'
            ]);
            if(!$category->id){
                $category->name_en  = 'douyin';
                $category->status   = 1;
                $category->user_id  = 1;
                $category->save();
            }

            $article              = new Article();
            $article->body        = $description;
            $article->status      = 1;
            $article->description = Str::limit($description, 280); //截取微博那么长的内容存简介
            $article->submit      = Article::SUBMITTED_SUBMIT; //直接发布
            $article->type        = 'post';
            $article->user_id     = $user->id;
            $article->category_id = $category->id;
            $article->save();

            //TODO 奖励接口
            return 'ok';

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500,$e->getMessage());
        }
    }
}
