<?php

namespace App;

use App\Exceptions\UserException;
use GraphQL\Type\Definition\ResolveInfo;
use Haxibiao\Media\Video as BaseVideo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Video extends BaseVideo
{
    //刷视频悬浮球奖励
    public  static  function videoPlayReward($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        $inputs = $args['input'];

        //今日通过 视频刷获取的积分
        $countReward = Gold::whereUserId($user->id)
            ->where('remark','like', '%视频观看时长奖励%')
            ->whereBetWeen('created_at', [today(), today()->addDay()])->sum('gold');
        if (Gold::TODAY_VIDEO_PLAY_MAX_GOLD<$countReward ) {
            return null;
        }

        //兼容老接口
        if (isset($inputs['video_id'])) {
            $video = \Haxibiao\Media\Video::find($inputs['video_id']);

            if (is_null($video)) {
                throw new UserException('视频不存在,请刷新后再试！');
            }

            $visited = $user->visitedArticles()->where('visited_id', $video->article->id)->first();
            if (!is_null($visited)) {
                return null;
            }

            // 判断用户最近15秒内有没有看视频，防止重刷
            if ($gold = $user->golds()->latest()->first()) {
                if ($gold->created_at->diffInRealSeconds(now()) < 15) {
                    return null;
                }
            }

            //随机奖励50~100

            //四舍五入 14.9  = 15
            $playDuration = round($inputs['play_duration']);

            /**
             * 奖励机制,详情见(http://pm2.haxibiao.com:8080/browse/JK-49)
             * 观看大于等于1分钟奖励 333医宝
             * 观看大于等于30秒 奖励 150医宝
             * 观看大于等于15秒 奖励 70医宝
             */
            $rewardGold = 0;
            if ($playDuration >= 60) {
                $rewardGold = 3;
            } else if ($playDuration >= 30) {
                $rewardGold = 2;
            } else if ($playDuration >= 15) {
                $rewardGold = 1;
            }

            //观看失败 或 观看时长不足
            if ($rewardGold <= 0) {
                return null;
            }

            Visit::createVisit($user->id, $video->article->id, 'articles');

            $remark = sprintf('<%s>观看奖励', $video->id);
            $gold = $user->goldWallet->changeGold($rewardGold, $remark);

            return $gold;
        }


        $video_ids  = $inputs['video_ids'];
        $rewardGold = random_int(1, 3);
        //大致统计用户浏览历史
        foreach ($video_ids as $video_id) {
            Visit::createVisit($user->id, $video_id, 'videos');
        }
        $remark = sprintf('视频观看时长奖励');
        $gold   = Gold::makeIncome($user, $rewardGold, $remark);

        return $gold;
    }
}
