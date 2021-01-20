<?php

namespace App;

use Haxibiao\Breeze\User;
use Haxibiao\Sns\Comment;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    //通知的主体信息
    public function getBodyAttribute()
    {
        //赞了你 评论的内容  @某某某 内容
        switch ($this->type) {
            case "App\\Notifications\\ArticleApproved":
                return "收录了动态";
            case "App\\Notifications\\ArticleRejected":
                return "拒绝了动态";
            case "App\\Notifications\\ArticleCommented":
                $comment = Comment::find($this->data['comment_id']);
                return str_limit($comment->body, 15, '...');
            case "App\\Notifications\\CommentedNotification":
                $comment = Comment::find($this->data['comment_id']);
                return str_limit($comment->body, 15, '...');
            case "App\\Notifications\\ArticleFavorited":
                return "收藏了动态";
            case "App\\Notifications\\ArticleLiked":
                return "喜欢了文章";
            case "App\\Notifications\\LikedNotification":
                $type = data_get($this, 'date.type');
                if ($type == 'comments') {
                    return "点赞了评论";
                }
                return "喜欢了动态";
            case "App\\Notifications\\CommentLiked":
                return "赞了评论";
            case "App\\Notifications\\ArticleTiped":
                return "打赏了动态";
            case "App\\Notifications\\CategoryFollowed":
                return "关注了专题";
            case "App\\Notifications\\CategoryRequested":
                return "投稿了专题";
            case "App\\Notifications\\CollectionFollowed":
                return "关注了文集";
            case "App\\Notifications\\UserFollowed":
                return "关注了";
            case "App\\Notifications\\ReplyComment":
                $comment = Comment::find($this->data['comment_id']);
                return str_limit($comment->body, 15, '...');
            case "App\\Notifications\\CommentAccepted":
                $comment = Comment::find($this->data['comment_id']);
                return str_limit($comment->body, 15, '...');
            case "App\\Notifications\\ReceiveAward":
                return $this->data["subject"] . $this->data["gold"] . '金币';
            default:
                return "其他";
        }
    }

    public function getTypeNameAttribute()
    {
        //回复了你的评论、在评论中提到了你...等等通知类型
        switch ($this->type) {
            case "App\\Notifications\\ArticleApproved":
                return "收录了动态";
            case "App\\Notifications\\ArticleRejected":
                return "拒绝了动态";
            case "App\\Notifications\\ArticleCommented":
                return "评论了动态";
            case "App\\Notifications\\CommentedNotification":
                return "评论了";
            case "App\\Notifications\\ArticleFavorited":
                return "收藏了动态";
            case "App\\Notifications\\ArticleLiked":
                return "喜欢了文章";
            case "App\\Notifications\\LikedNotification":
                if (data_get($this, 'data.type') == 'comments') {
                    return "点赞了评论";
                }
                return "喜欢了动态";
            case "App\\Notifications\\CommentLiked":
                return "赞了评论";
            case "App\\Notifications\\ArticleTiped":
                return "打赏了动态";
            case "App\\Notifications\\CategoryFollowed":
                return "关注了专题";
            case "App\\Notifications\\CategoryRequested":
                return "投稿了专题";
            case "App\\Notifications\\CollectionFollowed":
                return "关注了文集";
            case "App\\Notifications\\UserFollowed":
                return "关注了";
            case "App\\Notifications\\ReplyComment":
                return "回复了评论";
            case "App\\Notifications\\CommentAccepted":
                return "评论被采纳";
            case "App\\Notifications\\ReceiveAward":
                return $this->data["subject"] . $this->data["gold"] . '金币';
            default:
                return "其他";
        }
    }

    public function getTimeAgoAttribute()
    {
        return time_ago($this->created_at);
    }

    public function getUserAttribute()
    {
        if (isset($this->data['user_id'])) {
            $user = User::find($this->data['user_id']);
            return $user;
        }
        return null;
    }

    public function getArticleAttribute()
    {
        $modelType = data_get($this, 'data.type');
        if (!in_array($modelType, ['posts', 'comments'])) {
            return null;
        }
        if ($modelType == 'posts') {
            $modelId = data_get($this, 'data.id');
            if ($modelId) {
                $modelString = Relation::getMorphedModel($modelType);
                return $modelString::withTrashed()->find($modelId);
            }
            return null;
        }
        $comment = $this->getCommentAttribute();
        if (data_get($this, 'type') == 'App\Notifications\LikedNotification') {
            $commentable = data_get($comment, 'commentable');
            if ($commentable instanceof Comment) {
                return data_get($comment, 'commentable.commentable');
            }
            return data_get($comment, 'commentable');
        }
        return data_get($comment, 'commentable.commentable');
    }

    public function getPostAttribute()
    {
        $modelType = data_get($this, 'data.type');
        if (in_array($modelType, ['posts', 'comments'])) {
            return null;
        }
        if ($modelType == 'posts') {
            $modelId = data_get($this, 'data.id');
            if ($modelId) {
                $modelString = Relation::getMorphedModel($modelType);
                return $modelString::withTrashed()->find($modelId);
            }
            return null;
        }
        $comment = $this->getCommentAttribute();
        return data_get($comment, 'commentable.commentable');
    }

    public function getCommentAttribute()
    {
        $modelType = data_get($this, 'data.type');
        if ($modelType != 'comments') {
            return null;
        }
        $modelId = data_get($this, 'data.id');
        if ($modelId) {
            $modelString = Relation::getMorphedModel($modelType);
            return $modelString::withTrashed()->find($modelId);
        }
        return null;
    }

    public function getReplyAttribute()
    {
        return $this->getCommentAttribute();
    }
}
