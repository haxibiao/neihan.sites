<?php

namespace App;

use App\Profile;
use App\Traits\ContributeRepo;
use App\Traits\ContributeResolvers;
use Illuminate\Database\Eloquent\Model;

class Contribute extends Model
{

    use ContributeResolvers, ContributeRepo;

    // 发布有奖问答奖励贡献点汇率
    const ISSUE_CONVERSION_RATE = 10;

    // 提现金额与贡献值的汇率
    const WITHDRAW_DATE = 30;

    // 采纳问答奖励贡献点
    const REWARD_RESOLUTION_AMOUNT = 1;

    // 发布视频动态奖励贡献点
    const REWARD_VIDEO_POST_AMOUNT = 2;

    // DRAW 广告 morph 值
    const AD_CONTRIBUTED_ID   = 1;
    const AD_CONTRIBUTED_TYPE = 'AD';

    // 激励视频 广告 morph 值
    const VIDEO_CONTRIBUTED_ID   = 2;
    const VIDEO_CONTRIBUTED_TYPE = 'AD_VIDEO';

    // 点广告奖励值
    const AD_AMOUNT = 1;

    // 看激励视频奖励值
    const AD_VIDEO_AMOUNT = 3;

    // 点赞奖励值
    const LIKED_AMOUNT = 1;

    // 评论奖励值
    const COMMENTED_AMOUNT = 1;

//    视频刷点击广告奖励贡献值
    const REWARD_DRAW_AMOUNT = 1;
//    激励视频贡献值
    const REWARD_VIDEO_AMOUNT = 3;

    protected $guarded  = [];
    protected $fillable = [
        'user_id',
        'remark',
        'amount',
        'contributed_id',
        'contributed_type',
    ];

    protected $cast = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

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

    public static function rewardUserVideoPost($user, $article, $remark)
    {
        //发布视频动态奖励＋3贡献
        $contribute = self::firstOrNew(
            [
                'user_id'          => $user->id,
                'remark'           => $remark,
                'contributed_id'   => $article->id,
                'contributed_type' => 'articles',
            ]
        );
        $contribute->amount = self::REWARD_VIDEO_POST_AMOUNT;
        $contribute->recountUserContribute();
        $contribute->save();
        return $contribute;
    }

    public static function getAmount($gold)
    {
        return $gold / self::ISSUE_CONVERSION_RATE;
    }

    public static function rewardUserIssuePost($user, $issue, $amount, $remark)
    {
        $contribute = self::firstOrNew(
            [
                'user_id'          => $user->id,
                'remark'           => $remark,
                'contributed_id'   => $issue->id,
                'contributed_type' => 'issues',
            ]
        );

        $contribute->amount = $amount;
        $contribute->save();
        $contribute->recountUserContribute();

        return $contribute;
    }

    public static function rewardUserResolution($user, $resolution, $amount, $remark)
    {
        $contribute = self::firstOrNew(
            [
                'user_id'          => $user->id,
                'remark'           => $remark,
                'contributed_id'   => $resolution->id,
                'contributed_type' => 'resolutions',
            ]
        );
        $contribute->amount = $amount;
        $contribute->recountUserContribute();
        $contribute->save();
        return $contribute;
    }

    public function recountUserContribute()
    {
        $user = $this->user;
        Profile::where('user_id', $user->id)->increment('count_contributes', $this->amount);
    }

    public function getTimeAgoAttribute()
    {
        return time_ago($this->created_at);
    }

}
