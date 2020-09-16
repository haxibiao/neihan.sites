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
        'likable_id',
        'likable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 兼容旧接口用
    public function liked()
    {
        return $this->morphTo();
    }

    public function likable()
    {
        return $this->morphTo('likable');
    }

    public function scopeByLikableType($query, $likableType)
    {
        return $query->where('likable_type', $likableType);
    }

    public function scopeByLikableId($query, $likableId)
    {
        return $query->where('likable_id', $likableId);
    }

    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // 兼容旧接口用
    public function getLikedAttribute()
    {
        if ($user = getUser(false)) {
            return $user->likes()
                    ->byLikableType($this->likable_type)
                    ->byLikableId($this->likable_id)->count() > 0;
        }
        return false;
    }

    // 兼容旧接口用
    public function getPostAttribute(){
        return $this->likable;
    }

    // 兼容旧接口用
    public function getArticleAttribute()
    {
        return $this->likable;
    }

    // 兼容旧接口用
    public function getCommentAttribute()
    {
        return $this->likable;
    }
}
