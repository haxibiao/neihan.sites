<?php

namespace App;

use App\Model;
use App\Traits\CommentAttrs;
use App\Traits\CommentRepo;
use App\Traits\CommentResolvers;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Haxibiao\Sns\Traits\Likeable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Comment extends Model
{
    use Notifiable;
    use SoftDeletes;
    use CommentAttrs;
    use CommentRepo;
    use CommentResolvers;
    use Likeable;

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

    public static function boot()
    {
        parent::boot();
        //保存时触发
        self::saving(function ($comment) {
            $body          = $comment->body;
            $comment->body = app('SensitiveUtils')->replace($body, '*');
        });
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

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

    public function resolveComments($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $comment = self::findOrFail($rootValue->id);
        return $comment->comments();
    }

    public function getLikesAttribute()
    {
        return $this->likes()
            ->count();
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
}
