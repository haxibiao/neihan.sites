<?php

namespace App\Listeners;

use App\Events\LikeWasDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyedLike
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
     *
     * @param  LikeWasDeleted  $event
     * @return void
     */
    public function handle(LikeWasDeleted $event)
    {
        $like = $event->like; 
        $liked_obj = $like->liked;
        $authorizer = getUser();
        if($like->liked_type == 'comments'){
            $liked_obj->decrement('likes');
        //动态或文章
        } else if($like->liked_type == 'articles'){

            $target_user = $liked_obj->user;
            $liked_obj->decrement('count_likes');
            $target_user->decrement('count_likes');

        }
        \App\Action::where([
            'user_id' => $authorizer->id,
            'actionable_type' => 'likes',
            'actionable_id' => $like->id,
        ])->delete();
    }
}
