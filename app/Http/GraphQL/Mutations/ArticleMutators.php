<?php

namespace App\Http\GraphQL\Mutations;

use App\Article;
use App\Exceptions\GQLException;
use App\Exceptions\UserException;
use App\Gold;
use App\Image;
use App\Issue;
use App\Jobs\AwardResolution;
use App\Video;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleMutators
{

    public function createContent($root, array $args, $context)
    {
        //参数格式化
        $inputs = [
            'body'         => Arr::get($args, 'body'),
            'gold'         => Arr::get($args, 'issueInput.gold', 0),
            'category_ids' => Arr::get($args, 'category_ids', null),
            'images'       => Arr::get($args, 'images', null),
            'video_id'     => Arr::get($args, 'video_id', null),
        ];
        switch ($args['type']) {
            case 'issue':
                return $this->createIssue($inputs);
                break;
            case 'post':
                return $this->createPost($inputs);
                break;
            default:
                break;
        }
    }

    /**
     * 创建动态
     * body:    文字描述
     * category_ids:    话题
     * images:  base64图片
     * video_id: 视频ID
     */
    protected function createPost($inputs)
    {

        DB::beginTransaction();

        try {

            //带视频动态
            if ($inputs['video_id']) {
                $video             = Video::findOrFail($inputs['video_id']);
                $article           = $video->article;
                $article->type     = 'post';
                $article->status    = 1;
                $article->submit   = Article::REVIEW_SUBMIT;
                $article->title     = Str::limit($inputs['body'], 50);
                $article->description     = Str::limit($inputs['body'], 280);
                $article->body     = $inputs['body'];
                $article->video_id = $video->id; //关联上视频
                $article->save();

                //存文字动态或图片动态
            } else {
                $article              = new Article();
                $user                 = getUser();
                $body                 = $inputs['body'];
                $article->body        = $body;
                $article->status    = 1;
                $article->description = Str::limit($body, 280); //截取微博那么长的内容存简介
                $article->submit      = Article::SUBMITTED_SUBMIT; //直接发布
                $article->type        = 'post';
                $article->user_id     = $user->id;
                $article->save();

                if ($inputs['images']) {
                    foreach ($inputs['images'] as $image) {
                        $image = Image::saveImage($image);
                        $article->images()->attach($image->id);
                    }
                    $article->image_url = ($article->images()->first())->path;
                    $article->has_pic   = 1; //1代表内容含图
                    $article->save();
                }

                //带图
                if (!empty($inputs['image_urls']) && is_array($inputs['image_urls'])) {
                    $image_ids = array_map(function ($url) {
                        return intval(pathinfo($url)['filename']);
                    }, $inputs['image_urls']);
                    $article->image_url = $inputs['image_urls'][0];
                    $article->has_pic   = 1; //1代表内容含图
                    $article->status    = 1;
                    $article->images()->sync($image_ids);
                    $article->save();
                }
            };

            //直接关联到专题
            if ($inputs['category_ids']) {
                //排除重复专题
                $category_ids = array_unique($inputs['category_ids']);
                $category_id  = reset($category_ids);
                //第一个专题为主专题
                $article->category_id = $category_id;
                $article->save();

                if ($category_ids) {
                    $article->hasCategories()->sync($category_ids);
                }
            }

            DB::commit();
            app_track_post();
            return $article;
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($ex->getCode() == 0) {
                Log::error($ex->getMessage());
                throw new GQLException('程序小哥正在加紧修复中!');
            }
            throw new GQLException($ex->getMessage());
        }

    }

    /**
     * 创建问答
     * body:    文字描述
     * gold:    悬赏金额
     * video_id: 视频ID
     * category_ids:    话题
     * images:  base64图片
     */
    protected function createIssue($inputs)
    {
        DB::beginTransaction();

        try {
            $user           = getUser();
            $issue          = new Issue();
            $issue->user_id = $user->id;
            $body           = $inputs['body'];
            $issue->title   = $body;
            $issue->save();
            //视频问答
            if ($inputs['video_id']) {

                $user                 = getUser();
                $todayPublishVideoNum = $user->articles()
                    ->whereIn('type', ['post', 'issue'])
                    ->whereNotNull('video_id')
                    ->whereDate('created_at', Carbon::now())->count();
                if ($todayPublishVideoNum == 10) {
                    throw new UserException('每天只能发布10个视频动态!');
                }

                $video = Video::findOrFail($inputs['video_id']);
                if ($video->duration <= 5) {
                    throw new UserException('发布的视频不得低于5秒!');
                }

                //不能发布同一个视频（一模一样的视频）
                $videoToArticle = Article::where('user_id', $user->id)
                    ->where('video_id', $video->id)
                    ->whereIn('type', ['post', 'issue'])
                    ->count();
                if ($videoToArticle) {
                    throw new UserException('不能发布同一个视频');
                }

                $article              = $video->article;
                $article->body        = $body;
                $article->status      = 1;
                $article->description = $body;
                $article->title       = $body;
                //新创建的视频动态需要审核
                $article->submit   = Article::REVIEW_SUBMIT;
                $article->issue_id = $issue->id;
                $article->type     = 'issue';
                $article->save();
            } else if ($inputs['images']) {

                $article              = new Article();
                $article->body        = $body;
                $article->status      = 1;
                $article->description = $body;
                $article->title       = $body;
                $article->issue_id    = $issue->id;
                $article->type        = 'issue';
                $article->save();

                foreach ($inputs['images'] as $image) {
                    $image = Image::saveImage($image);
                    $article->images()->attach($image->id);
                }
                $article->image_url = ($article->images()->first())->path;
                $article->has_pic   = 1; //1代表内容含图
                $article->save();
            }
            //付费问答(金币)
            if ($inputs['gold'] > 0) {
                if ($user->gold < $inputs['gold']) {
                    throw new UserException('您的金币不足!');
                }
                //扣除金币
                Gold::makeOutcome($user, $inputs['gold'], '悬赏问答支付');
                $issue->gold = $inputs['gold'];
                $issue->save();

                //带图问答不用审核，直接触发奖励
                if ($article->type = 'issue' && is_null($article->video_id)) {
                    AwardResolution::dispatch($issue)
                        ->delay(now()->addDays(7));
                }
            }
            //直接关联到专题
            if ($inputs['category_ids']) {
                //排除重复专题
                $category_ids = array_unique($inputs['category_ids']);
                $category_id  = reset($category_ids);
                //第一个专题为主专题
                $article->category_id = $category_id;
                $article->save();

                if ($category_ids) {
                    $article->hasCategories()->sync($category_ids);
                }
            }

            DB::commit();
            app_track_issue();
            return $article;
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($ex->getCode() == 0) {
                Log::error($ex->getMessage());
                throw new GQLException('程序小哥正在加紧修复中!');
            }
            throw new GQLException($ex->getMessage());
        }
    }
}
