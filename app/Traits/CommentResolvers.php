<?php
namespace App\Traits;

use App\Article;
use App\Comment;
use App\Contribute;
use App\Exceptions\GQLException;
use App\Exceptions\UserException;
use App\Gold;
use App\Resolution;
use Haxibiao\Helpers\BadWordUtils;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait CommentResolvers
{
    public function resovleComments($root, array $args, $context){

        $query = Comment::orderBy('is_accept','desc')
            ->orderBy('id','desc');

        $query->when( isset($args['commentable_id'] ) , function ($q) use ($args){
            return $q->where('commentable_id', $args['commentable_id']);
        });

        $query->when( isset($args['commentable_type'] ) , function ($q) use ($args){
            $commentable_type = $args['commentable_type'];
            if($args['commentable_type'] == 'articles'){
                $commentable_type = 'posts';
            }
            return $q->where('commentable_type', $commentable_type);
        });

        $query->when( isset($args['user_id'] ) , function ($q) use ($args){
            return $q->where('user_id', $args['user_id']);
        });
        return $query;
    }

    /**
     * 创建评论
     * @param $root
     * @param array $args
     * @param $context
     * @return Comment
     * @throws \App\Exceptions\UnregisteredException
     */
    public function create($root, array $args, $context)
    {

        $user = getUser();
        if ($user->isBlack()) {
            throw new GQLException('发布失败,你以被禁言');
        }

        if (BadWordUtils::check($args['body'])) {
            throw new GQLException('发布的评论中含有包含非法内容,请删除后再试!');
        }

        // 临时兼容comments
        $commentable_type = $args['commentable_type'];

        // 注释的原因：问答这块的逻辑与Article分开
//        if ($args['commentable_type'] == 'articles') {
//            $article = Article::findOrFail($args['commentable_id']);
//            $type    = $article->type;
//            //评论视频问答
//            if ($type == 'issue') {
//                $issue = $article->issue;
//
//                //该问答未结束
//                if (!$issue->closed) {
//                    //之前回答过这个问题，变成补充。
//                    $comment = $article->comments()
//                        ->where('user_id', $user->id)->first();
//                    if ($comment) {
//                        $comment->body = $comment->body . "\r\n\r\n" . now() . '补充：' . "\r\n" . $args['body'];
//                        $comment->save();
//                        return $comment;
//                    }
//                }
//            }
//        }
        $comment                   = new Comment();
        $comment->user_id          = $user->id;
        $comment->commentable_type = $commentable_type;
        $comment->commentable_id   = $args['commentable_id'];
        $comment->body             = $args['body'];
        $comment->save();
        app_track_event('用户',"评论");
        return $comment;
    }

    /**
     * 采纳用户的评论成为答案
     * @param $root
     * @param array $args
     * @param $context
     * @return mixed
     * @throws GQLException
     * @throws \App\Exceptions\UnregisteredException
     */
    public function accept($root, array $args, $context)
    {

        DB::beginTransaction();
        $user = getUser();
        if ($user->isBlack()) {
            throw new GQLException('发布失败,你以被禁言');
        }

        try {
            $comment_ids = Arr::get($args, 'comment_ids');
            $comments    = Comment::find($comment_ids);
            $comment     = $comments->first();
            if (BadWordUtils::check($comment->body)) {
                throw new GQLException('发布的评论中含有包含非法内容,请删除后再试!');
            }
            $commentable = $comment->commentable;
            $issue       = $commentable->issue;
            $gold        = $issue->gold;

            if ($issue->closed) {
                throw new UserException('该问题已被解决!');
            }

            //该问题是免费问答
            if ($gold == 0) {
                foreach ($comments as $comment) {
                    $resolution           = new Resolution();
                    $resolution->answer   = $comment->body;
                    $resolution->user_id  = $comment->user_id;
                    $resolution->issue_id = $commentable->issue_id;
                    $resolution->save();

                    //该评论被采纳
                    $comment->is_accept = true;
                    $comment->save();
                }
                //悬赏问答
            } else {
                $individual = $gold / count($comment_ids);
                foreach ($comments as $comment) {
                    $resolution           = new Resolution();
                    $resolution->answer   = $comment->body;
                    $resolution->user_id  = $comment->user_id;
                    $resolution->issue_id = $commentable->issue_id;
                    $resolution->gold     = $individual;
                    $resolution->save();

                    //该评论被采纳
                    $comment->is_accept = true;
                    $comment->save();

                    $toUser = $comment->user;
                    Gold::makeIncome($toUser, $individual, '答案被采纳奖励');

                    // 奖励贡献点
                    Contribute::rewardUserResolution($user, $resolution, Contribute::REWARD_RESOLUTION_AMOUNT, "答案被采纳奖励");

                    //评论被采纳
                    $toUser->notify(new \App\Notifications\CommentAccepted($comment, $user));
                }
            }

            //问题被解决
            $issue->closed = true;
            $issue->save();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($ex->getCode() == 0) {
                Log::error($ex->getMessage());
                throw new GQLException('程序小哥正在加紧修复中!');
            }
            throw new GQLException($ex->getMessage());
        }

        return $comments;
    }
}