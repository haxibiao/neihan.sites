<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Dimension extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'value',
        'count',
        'created_at',
        'hour',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePlayVideo($query)
    {
        return $query->where('name', 'VIDEO_PLAY_REWARD');
    }

    //repo

    //维度统计（广告）
    public static function trackAd($adName = "激励视频")
    {
        self::setDimension(getUser(), $adName);
    }

    //更新维度统计
    public static function setDimension($user, $name, int $value = 1)
    {
        $userId = $user->id;
        //每天一个维度统计一到一个记录里
        $dimension = Dimension::where('user_id', $userId)->where('name', $name)
            ->today()
            ->latest('id')
            ->first();
        if (is_null($dimension)) {
            return $dimension = Dimension::create([
                'user_id' => $userId,
                'name'    => $name,
                'value'   => $value,
            ]);
        } else {
            //更新数值和统计次数
            $dimension->update(['value' => DB::raw('value +' . $value), 'count' => DB::raw('count + 1')]);
        }

        return $dimension;
    }

    public static function getInActiveUserAvgGold()
    {
        return Dimension::where('user_id', 0)->where('name', 'inactive_user_avg_gold')->first();
    }

    public static function getAdNames()
    {
        // 视频类广告 draw 只有点击了才有奖励..
        // 所以基于奖励接口
        // 激励，全屏能统计完全播放数和点击数，
        // draw只有点击数
        // 信息流只有点击数
        // 开屏暂时无法统计
        return [
            '激励视频',
            '全屏视频',
            'draw视频',
            '开屏',
            '信息流',
        ];
    }
}
