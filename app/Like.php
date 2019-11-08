<?php

namespace App;

use App\Article;
use App\Comment;
use App\Model;
use App\Traits\LikeRepo;
use App\Traits\LikeResolvers;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use LikeResolvers;
    use LikeRepo;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'liked_id',
        'liked_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liked()
    {
        return $this->morphTo();
    }

    //兼容likable的写法
    public function likable()
    {
        return $this->morphTo('liked');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'liked_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'liked_id');
    }

    // scope

    public function scopeOfType($query, $type)
    {
        return $query->where('liked_type', $type);
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

}
