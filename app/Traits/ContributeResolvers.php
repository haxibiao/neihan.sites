<?php

namespace App\Traits;

use App\Contribute;
use App\Gold;
use App\User;
use App\Version;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait ContributeResolvers
{
    /**
     * Draw广告奖励
     * self::DRAW_AD_CONTRIBUTED_TYPE, contributes表中 Draw广告 morph type 值
     * self::DRAW_AD_CONTRIBUTED_ID, contribute表中 Draw广告 morph id 值
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return Contribute
     */
    public function clickDraw($rootValue, array $args, $context, $resolveInfo)
    {
        if ($user = checkUser()) {
//            今日draw广告点击次数
            $count = self::getToDayCountByTypeAndId(self::DRAW_AD_CONTRIBUTED_TYPE, self::DRAW_AD_CONTRIBUTED_ID, $user);
            if ($count <= self::MAX_DRAW_CLICK) {
                $contribute = self::rewardUserContribute($user->id, self::DRAW_AD_CONTRIBUTED_ID, self::REWARD_DRAW_AMOUNT,
                    self::DRAW_AD_CONTRIBUTED_TYPE, "刷视频奖励");
                $contribute->message = '恭喜，获得' . $contribute->amount . '点贡献';
                return $contribute;
            }
//          兼容旧版本
            $contribute          = new Contribute();
            $contribute->amount  = 0;
            $contribute->message = '您已超过正常下载行为...';
            return $contribute;
        }

        $contribute          = new Contribute();
        $contribute->amount  = 0;
        $contribute->message = '请先登录哦！~';
        return $contribute;
    }

    /**
     * 激励视频广告奖励
     * $isClick = true 即用户点击了广告详情
     * self::REWARD_VIDEO_CONTRIBUTED_TYPE,contributes表中 激励视频 morph type 值
     * self::REWARD_VIDEO_CONTRIBUTED_ID,contributes表中 激励视频 morph id 值
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return array
     */
    public function clickRewardVideo($rootValue, array $args, $context, $resolveInfo)
    {
        if ($user = checkUser()) {

            //激励视频奖励只允许最新版本的用户获得
            //http://pm.haxibiao.com:8080/browse/DDZ-488
            if ($result = $this->checkVersion($user)) {
                return $result;
            }
            $count = self::getCountByType(Contribute::REWARD_VIDEO_CONTRIBUTED_TYPE, $user);
            if ($result = $this->checkUser($user, $count)) {
                return $result;
            }

            $remark = '激励视频奖励';
            if ($user->status == \App\User::STATUS_FREEZE) {
                $gold = Gold::makeIncome($user, Gold::REWARD_VIDEO_GOLD, $remark);
                return [
                    'message'    => $remark,
                    'gold'       => $gold->gold,
                    'contribute' => 0,
                ];
            }

            $isClick    = $args['is_click'];
            $contribute = null; //激励视频永远至少+2贡献

            $gold = Gold::makeIncome($user, Gold::REWARD_VIDEO_GOLD, $remark);

            // 前30次, 点击了能获取贡献点*2
            if ($isClick && $count < 30) {
                $contribute = self::rewardUserContribute($user->id, self::REWARD_VIDEO_CONTRIBUTED_ID,
                    self::AD_VIDEO_AMOUNT * 2, self::REWARD_VIDEO_CONTRIBUTED_TYPE, "点击激励视频");
            } else {
                $contribute = self::rewardUserContribute($user->id, self::REWARD_VIDEO_CONTRIBUTED_ID,
                    self::AD_VIDEO_AMOUNT, self::REWARD_VIDEO_CONTRIBUTED_TYPE, "看激励视频");
            }

            //  拼接前端所需信息
            $message = $contribute === null ? $remark : $remark . '、贡献点';

            return [
                'message'    => $message,
                'gold'       => $gold->gold,
                'contribute' => $contribute === null ? null : $contribute->amount,
            ];
        }
        return [
            'message'    => '请先登录哦！~',
            'gold'       => 0,
            'contribute' => 0,
        ];
    }

    public function resolveContributes($rootValue, array $args, $context, $resolveInfo)
    {
        app_track_user("查看贡献点", 'list_contributes', getUserId());
        return Contribute::orderBy('id', 'desc')->where('user_id', $args['user_id']);
    }

//    兼容旧版本Feed
    public function clickFeedAD($rootValue, array $args, $context, $resolveInfo)
    {
        if ($user = checkUser()) {
            if (Contribute::getToDayCountByTypeAndId(self::AD_FEED_CONTRIBUTED_TYPE, self::AD_FEED_CONTRIBUTED_ID,
                $user) <= 10) {
                $contribute = Contribute::rewardUserContribute($user->id, self::AD_FEED_CONTRIBUTED_ID,
                    self::AD_AMOUNT,
                    self::AD_FEED_CONTRIBUTED_TYPE, "看发现页面广告奖励");
                $contribute->message = '看广告奖励' . $contribute->amount . '点贡献,谢谢您的支持！~';
                return $contribute->amount;
            }
        }
        return 0;
    }

    /**
     * 目前正在使用的点击Feed广告奖励
     * self::AD_FEED_CONTRIBUTED_TYPE,morph Type
     * self::AD_FEED_CONTRIBUTED_ID,morph Id
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return Contribute|\Illuminate\Database\Eloquent\Model
     */
    public function clickFeedAD2($rootValue, array $args, $context, $resolveInfo)
    {
        if ($user = checkUser()) {
            if (Contribute::getToDayCountByTypeAndId(self::AD_FEED_CONTRIBUTED_TYPE, self::AD_FEED_CONTRIBUTED_ID, $user) <= self::MAX_FEED_CLICK) {
                $contribute = Contribute::rewardUserContribute($user->id, self::AD_FEED_CONTRIBUTED_ID, self::AD_AMOUNT,
                    self::AD_FEED_CONTRIBUTED_TYPE, "发现页广告奖励");
                $contribute->message = '您刚获得' . $contribute->amount . '点贡献';
                return $contribute;
            }
            //          兼容旧版本
            $contribute          = new Contribute();
            $contribute->amount  = 0;
            $contribute->message = '您已超过正常下载行为...';
            return $contribute;
        }

        $contribute          = new Contribute();
        $contribute->amount  = 0;
        $contribute->message = '请先登录哦！~';
        return $contribute;
    }

    public function checkUser($user, $todayCount)
    {
        if ($todayCount >= 30) {
            return [
                'message'    => '今天次数用完啦',
                'gold'       => 0,
                'contribute' => 0,
            ];
        }

        $lastRewardContribute = $user->contributes()
            ->select('created_at')
            ->where('contributed_type', Contribute::REWARD_VIDEO_CONTRIBUTED_TYPE)
            ->latest('id')
            ->first();

        // 上次看激励视频与本次间隔 < 60 秒
        if (now()->diffInSeconds(Carbon::parse($lastRewardContribute->created_at)) < 60) {
            $user->update(['status' => User::STATUS_FREEZE]);
            return [
                'message'    => '行为异常,详情咨询QQ群:808982693',
                'gold'       => 0,
                'contribute' => 0,
            ];
        }
    }

    public function checkVersion($user)
    {
        $version       = substr($user->profile->app_version, 0, 3);
        $latestVersion = Version::getLatestVersion();
        if ($user->profile->app_version === null || !Str::contains($latestVersion->name, $version)) {
            return [
                'message'    => '请使用最新版本',
                'gold'       => 0,
                'contribute' => 0,
            ];
        }
    }
}
