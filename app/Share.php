<?php

namespace App;

use App\Events\ShareableWasVisited;
use App\Traits\ShareableBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Share extends Model
{
    protected $guarded = [];

    protected $casts = [
        'uids' => 'array',
    ];

    public static function buildFor($model)
    {
        return new ShareableBuilder($model);
    }

    public function shareable()
    {
        return $this->morphTo();
    }


    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    public function isExpired(): bool
    {
        if ($this->expired_at === null) {
            return false;
        }

        return Carbon::now()->greaterThan(Carbon::parse($this->expired_at));
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function video(){
        return $this->shareable();
    }

    public function getPostAttribute()
    {
        $video = $this->video;
        return Post::where('video_id',$video->id)->first();
    }

    public function visitShareUuid($root, $args, $context){

        $uuid = data_get($args,'uuid');
        $share = Share::where('uuid', $uuid)
            ->firstOrFail();
        $user = getUser(false);

        if(!$user){
            return $share;
        }
        $isSelf = $user->id == $share->user_id;
        if (!$isSelf && $share->isActive() && !$share->isExpired()) {

            //去除重复访问
            $uids = data_get($share,'uids',array());
            $hasVisited = in_array($user->id,$uids);
            if(!$hasVisited){
                array_push($uids,$user->id);
                $share->update([
                    'uids' => $uids,
                ]);
                //触发奖励策略
                event(new ShareableWasVisited($share));
            }
        }
        return $share;
    }
}
