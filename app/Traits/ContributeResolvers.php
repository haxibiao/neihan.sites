<?php

    namespace App\Traits;

    use App\Contribute;
    use App\Gold;

    trait ContributeResolvers
    {
        public function clickAD($rootValue, array $args, $context, $resolveInfo)
        {
            if ($user = checkUser()){
                if(Contribute::getToDayCountByTypeAndId(self::AD_CONTRIBUTED_TYPE,self::AD_CONTRIBUTED_ID,$user) <= 10){
                    $contribute = Contribute::rewardUserContribute($user->id, self::AD_CONTRIBUTED_ID, self::AD_AMOUNT,
                        self::AD_CONTRIBUTED_TYPE, "刷视频奖励");
                    $contribute->message = '看广告奖励'.$contribute->amount.'点贡献,谢谢您的支持！~';
                    return $contribute;
                }
//          兼容旧版本
                $contribute = new Contribute();
                $contribute->amount = 0;
                $contribute->message = '今天已经看了10次首页上广告了哦~,快去尝试一下看视频广告吧,记得点击详情,奖励更加丰厚！~';
                return $contribute;
            }

            $contribute = new Contribute();
            $contribute->amount = 0;
            $contribute->message = '请先登录哦！~';
            return $contribute;
        }

        public function playADVideo($rootValue, array $args, $context, $resolveInfo)
        {
            $user = checkUser();
            $count = Contribute::getCountByType(Contribute::VIDEO_CONTRIBUTED_TYPE, $user);
            $isClick = $args['is_click'];
            $contribute = null;
//            每天看激励视频获取贡献点限制30次
            if ($count < 30) {
                $remark = '看激励视频获取智慧点奖励';
                $gold = Gold::makeIncome($user, Gold::REWARD_VIDEO_AMOUNT, $remark);
                if ($isClick) {
                    $contribute = Contribute::rewardUserContribute($user->id, self::VIDEO_CONTRIBUTED_ID,
                        self::AD_VIDEO_AMOUNT, self::VIDEO_CONTRIBUTED_TYPE, "观看激励视频奖励");
                }else{
                    $contribute = Contribute::rewardUserContribute($user->id, self::VIDEO_CONTRIBUTED_ID,
                        2, self::VIDEO_CONTRIBUTED_TYPE, "观看激励视频奖励");
                }
            } else {
//            超出30次限制，奖励双倍金币
                $goldAmount = Gold::REWARD_VIDEO_AMOUNT * 2;
                $remark = '看激励视频获取双倍智慧点奖励';
                $gold = Gold::makeIncome($user, $goldAmount, $remark);
            }
//            拼接前端所需信息
            $message = is_null($contribute) ? $remark : $remark . '、贡献点';

            return [
                'message' => $message,
                'gold' => $gold->gold,
                'contribute' => is_null($contribute) ? null : $contribute->amount,
            ];
        }

        public function resolveContributes($rootValue, array $args, $context, $resolveInfo)
        {
            app_track_user("查看贡献点", 'list_contributes', getUserId());
            return Contribute::orderBy('id', 'desc')->where('user_id', $args['user_id']);
        }

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

        public function clickFeedAD2($rootValue, array $args, $context, $resolveInfo){
            if ($user = checkUser()){
                if(Contribute::getToDayCountByTypeAndId(self::AD_FEED_CONTRIBUTED_TYPE,self::AD_FEED_CONTRIBUTED_ID,$user) <= 10){
                    $contribute = Contribute::rewardUserContribute($user->id, self::AD_FEED_CONTRIBUTED_ID, self::AD_AMOUNT,
                        self::AD_FEED_CONTRIBUTED_TYPE, "看发现页面广告奖励");
                    $contribute->message = '看广告奖励'.$contribute->amount.'点贡献,谢谢您的支持！~';
                    return $contribute;
                }
                //          兼容旧版本

                $contribute = new Contribute();
                $contribute->amount = 0;
                $contribute->message = '今天已经看了10次发现页面上广告了哦~,快去尝试一下看视频广告吧,记得点击详情,奖励更加丰厚！~';
                return $contribute;
            }

            $contribute = new Contribute();
            $contribute->amount = 0;
            $contribute->message = '请先登录哦！~';
            return $contribute;
        }
    }
