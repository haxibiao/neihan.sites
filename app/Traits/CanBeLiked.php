<?php


namespace App\Traits;


use App\Like;

trait CanBeLiked
{
    public static function bootCanBeLiked()
    {
        static::deleting(function($model) {
            $model->likes()->delete();
            $model->count_likes = 0;
            $model->save();
        });
    }
// FIXME 暂时注释避免冲突
//    public function likes()
//    {
//        return $this->morphMany(Like::class, 'likable');
//    }

    public function getLikedAttribute()
    {
        if ( checkUser() ) {
            return $this->isLiked();
        }
        return false;
    }

    // 兼容旧接口用
    public static function likedPosts($user, $posts)
    {
        $postIds = $posts->pluck('id');
        if (count($postIds) > 0) {
            $likedIds = $user->likedTableIds('posts', $postIds);
            //更改liked状态
            $posts->each(function ($post) use ($likedIds) {
                $post->liked = $likedIds->contains($post->id);
            });
        }

        return $posts;
    }

    // 兼容旧接口用
    public function getLikedIdAttribute()
    {
        if ($user = getUser(false)) {
            $like = $user->likedArticles()->where('likable_id', $this->id)->first();
            return $like ? $like->id : 0;
        }
        return 0;
    }

    public function likeIt($user = null)
    {
        if(is_null($user)) {
            $user = getUser();
        }

        if($user) {
            $like = $this->likes()
                ->where('user_id', '=', $user->id)
                ->first();

            if($like) {
                return;
            }

            $like = new Like();
            $like->user_id = $user->id;
            $this->incrementLikeCount();
            return $this->likes()->save($like);
        }
    }

    public function unLikeIt($user = null)
    {
        if(is_null($user)) {
            $user = getUser();
        }

        if($user) {
            $like = $this->likes()
                ->where('user_id', '=', $user->id)
                ->first();

            if(!$like) { return; }

            $like->delete();
            $this->decrementLikeCount();
            return $like;
        }
    }

    public function toggleLike($user = null)
    {
        return $this->isLiked($user) ? $this->unLikeIt($user) : $this->likeIt($user);
    }

    public function isLiked($user = null)
    {
        return  (bool) $this->likes()
            ->where('user_id', '=', $user ? $user->id : getUser()->id)
            ->count();
    }

    public function decrementLikeCount($count=1)
    {
        if($count <= 0) { return; }

        $this->count_likes = $this->count_likes - $count;
        if($this->count_likes < 0) {
            $this->count_likes = 0;
        }
        $this->save();
    }

    public function incrementLikeCount($count=1)
    {
        if($count <= 0) { return; }
        $this->count_likes = $this->count_likes + $count;
        $this->save();
    }
}