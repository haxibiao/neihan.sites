<?php

namespace App\Events;

use App\Like;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLike implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $like;
    public $likable;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function broadcastOn()
    {
        $this->likable = $this->like->likable;
        if (!is_null($this->likable->user_id)) {
            return new PrivateChannel('App.User.' . $this->likable->user_id);
        }
    }

    public function broadcastWith()
    {
        $data     = [];
        $likable  = $this->likable;
        $likeUser = $this->like->user;
        if (!is_null($likeUser)) {

            if ($likable instanceof \App\Article) {
                $moudle  = '动态';
                $content = str_limit(strip_tags($likable->body), 5);
            } else if ($likable instanceof \App\Comment) {
                $moudle  = '评论';
                $content = str_limit(strip_tags($likable->body), 5);
            }

            if (isset($moudle)) {
                $content = sprintf('%s 刚刚点赞了你的%s《%s》', $likeUser->name, $moudle, $content);
                $data    = [
                    'title'        => '新点赞提醒',
                    'like_content' => $content,
                    'like_id'      => $this->like->id,
                    'likeable_id'  => $this->likable->id,
                    'user_id'      => $this->like->user->id,
                    'user_avatar'  => $this->like->user->avatar,
                    'user_name'    => $this->like->user->name,
                ];
            }
        }
        return $data;
    }
}
