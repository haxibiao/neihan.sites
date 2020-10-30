<?php
namespace App\Traits;


use App\CheckIn;
use App\Contribute;
use App\Gold;

trait CheckInFacade
{
    public static function checkYesterdaySignIned($profile)
    {
        $keepSignDays      = $profile->keep_checkin_days;
        $yesterDaySignIned = false;
        //昨日是否签到
        if ($keepSignDays >= 1) {
            $yesterDaySignIned = CheckIn::yesterdaySigned($profile->user_id);
            $todaySigned       = CheckIn::todaySigned($profile->user_id);
            if (!$todaySigned) {
                //未签到或者签到超出最大天数 && 清除
                if (!$yesterDaySignIned || $keepSignDays >= CheckIn::MAX_SIGNIN_DAYS) {
                    $profile->update(['keep_checkin_days' => 0]);
                }
            }
        }

        return $yesterDaySignIned;
    }

    public static function getSignInReward($day)
    {
        $goldReward       = 0;
        $contributeReward = 0;
        switch ($day) {
            case 1:
                $goldReward = 10;
                break;
            case 2:
                $goldReward = 10;
                break;
            case 3:
                $goldReward = 50;
                break;
            case 7:
                $goldReward = 50;
                break;
            default:
                $goldReward  = 20;
                break;
        }

        $rewards = [
            'gold_reward'       => $goldReward,
            'contribute_reward' => $contributeReward,
        ];

        return $rewards;
    }

    public static function keepSignInReward(CheckIn $signIn, $keepSignDays)
    {
        $user = $signIn->user;

        //奖励天数0-6
        $rewards          = self::getSignInReward($keepSignDays);
        $goldReward       = $rewards['gold_reward'];
        $contributeReward = $rewards['contribute_reward'];

        //七天奖励18贡献额度
        if ($goldReward == 0 && $keepSignDays >= 7) {
        //todo 签到七天，增加一次提现机会

        } else {
            // Gold::makeIncome($user, $goldReward, '连续签到奖励');
        }
        //保存奖励
        $signIn->gold_reward       = $goldReward;
        $signIn->contribute_reward = $contributeReward;
        $signIn->save();
    }
}
