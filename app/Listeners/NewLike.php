<?php

namespace App\Listeners;

use App\Events\LikeWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;

class NewLike
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * TODO 这个地方处理的时候还存在问题,框架内部自动加载了commentable，user关系
     * 
     * @param  LikeWasCreated  $event
     * @return void
     */
    public function handle(LikeWasCreated $event)
    {
        $like = $event->like; 
        $liked_obj = $like->liked; 
        $target_user = $liked_obj->user;//文章作者或者发布评论的用户
        $authorizer = getUser();        //当前登录用户
        $cacheKey = 'user_' . $authorizer->id . '_like_' . $like->liked_id . '_' . $like->liked_type;
        if($like->liked_type == 'comments'){
            if (!Cache::get($cacheKey) && ($authorizer->id != $target_user->id)) {
                $target_user->notify(new \App\Notifications\ArticleLiked( $liked_obj->commentable->id, $authorizer->id, $liked_obj)); 
                $target_user->forgetUnreads();//清除缓存
                Cache::put($cacheKey, 1, 60);
            }
            $liked_obj->increment('likes');
        //动态或文章
        } else if($like->liked_type == 'articles'){ 

            if (!Cache::get($cacheKey) && ($authorizer->id != $target_user->id)) {
                $target_user->notify(new \App\Notifications\ArticleLiked($like->liked_id, $authorizer->id));
                $target_user->forgetUnreads();//清除缓存
                Cache::put($cacheKey, 1, 60);
            }
            $liked_obj->increment('count_likes');
            $target_user->increment('count_likes');
        }
        //记录操作日志
        $action = \App\Action::updateOrCreate([ 
            'user_id'         => $authorizer->id,
            'actionable_type' => 'likes',
            'actionable_id'   => $like->id,
        ]);
    }
}
