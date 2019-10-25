<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribute extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function contributed()
    {
        return $this->morphTo();
    }

    public function question()
    {
        return $this->belongsTo(Article::class, 'contributed_id');
    }

    public static function rewardUserVideoPost($user, $articles)
    {
        //发布视频动态奖励＋3贡献
        $contribute = self::firstOrNew(
            [
                'user_id'          => $user->id,
                'contributed_id'   => $articles->id,
                'contributed_type' => 'articles',
            ]
        );
        $contribute->amount = 3;
        $contribute->save();
        return $contribute;
    }
}
