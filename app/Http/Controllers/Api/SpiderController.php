<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\User;
use App\Video;
use App\Article;
use App\Category;
use Haxibiao\Media\Spider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpiderController extends Controller
{
    public function importDouyinSpider(Request $request)
    {
        try {
            $video_url = $request->video_url;
            $account   = $request->account;
            $metaInfo  = $request->description;

            $description = Str::replaceFirst('#在抖音，记录美好生活#', '', $metaInfo);
            if (Str::contains($description, '#')) {
                $description = Str::before($description, '#');
            } else {
                $description = Str::before($description, 'http');
            }

            $user = User::where('account', $account)
                ->orWhere('email', $account)
                ->firstOrFail();
            Auth::login($user);
            $hash  = md5_file($video_url);
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            $video->setJsonData('metaInfo', $metaInfo);
            $video->user_id = $user->id;
            $video->title   = $description;
            $video->save(); //不触发事件通知

            //本地存一份用于截图
            $cosPath     = 'video/' . $video->id . '.mp4';
            $video->path = $cosPath;
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
                'name' => '我要上热门',
            ]);
            if (!$category->id) {
                $category->name_en = 'douyin';
                $category->status  = 1;
                $category->user_id = 1;
                $category->save();
            }

            $article              = new Article();
            $article->body        = $description;
            $article->status      = 1;
            $article->description = Str::limit($description, 280); //截取微博那么长的内容存简介
            $article->submit      = Article::SUBMITTED_SUBMIT; //直接发布
            $article->type        = 'post';
            $article->user_id     = $user->id;
            $article->video_id    = $video->id;
            $article->category_id = $category->id;
            $article->save();

            //TODO 奖励接口
            return 'ok';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500, $e->getMessage());
        }
    }

    public function hook(Request $request)
    {
        $sourceUrl = $request->get('source_url');
        $data      = $request->get('data');
        if (!empty($sourceUrl)) {
            $article = Article::where('source_url', $sourceUrl)
                ->where('submit', Article::REVIEW_SUBMIT)
                ->first();
            $video = Arr::get($data, 'video');
            if (!is_null($article) && is_array($video)) {
                $article->processSpider($data);
            }
        }
    }

    //文件上传批量解析抖音链接
    public function importDouYin(Request $request)
    {
        $file = $request->file('file');
        if ($file->isValid()) {
            $str = file_get_contents($file->getRealPath());
            $str = trim($str);
            $array = explode(',', $str);

            $count = 0;
            for ($i = 0; $i < count($array); $i++) {
                try {
                    $spider_link = str_before($array[$i], '#');
                    $spider = Spider::resolveDouyinVideo(getUser(), $spider_link);
                    $post = Post::with('video')->firstOrNew(['spider_id' => $spider->id]);
                    //标签
                    $tags = str_after($array[$i], '#');
                    $tags = str_replace(' ', '', $tags);
                    $tagNames = explode('#', $tags);
                    $post->tagByNames($tagNames ?? []);

                    $post->user_id = getUser()->id;
                    $post->save();

                    $count++;
                    sleep(5);
                } catch (Exception $e) {
                    continue;
                }
            }
            Log::info("成功解析" . $count . "条抖音分享");
        }
    }
}
