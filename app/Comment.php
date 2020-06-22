<?php

namespace App;

use App\Model;
use App\Traits\CommentAttrs;
use App\Traits\CommentRepo;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Comment extends Model
{
    use SoftDeletes;
    use CommentAttrs;
    use CommentRepo;

    protected $touches = ['commentable'];

    public $fillable = [
        'user_id',
        'body',
        'comment_id',
        'commentable_id',
        'commentable_type',
        'lou',
        'status',
    ];

    public function commented()
    {
        return $this->belongsTo(\App\Comment::class, 'id');
    }

    public function replyComments()
    {
        return $this->morphMany(\App\Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCountRepliesAttribute()
    {
        return $this->comments()->count();
    }

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function getLikesAttribute()
    {
        return $this->likes()
            ->count();
    }

    //上面的likes模型方法与comments数据库字段重名了，导致vue数据访问错误。
    //现增加下面的方法用于区分  TODO: likes字段应该重命名为 count_likes
    public function hasManyLikes()
    {
        return $this->likes();
    }

    public function article()
    {
        return $this->belongsTo(\App\Article::class, 'commentable_id');
    }

    // attrs

    public function getRepliesAttribute()
    {
        return $this->replyComments()->latest('id')->take(20)->get();
    }

    public function getContent()
    {
        return str_limit(strip_tags($this->body), 5) . '..';
    }

    public function getArticleAttribute()
    {
        return $this->article()->first();
    }

    //resolvers

    public function resolveComments($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event('用户页', '获取评论列表');

        $comment = self::findOrFail($rootValue->id);
        return $comment->comments();
    }
}
