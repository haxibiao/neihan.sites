<?php
namespace App\Traits;

use App\Aso;
use App\Exceptions\GQLException;
use App\Gold;
use App\Video;
use App\Visit;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait VideoResolvers
{
    public function videoPlayReward($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user('激励视频奖励');

        $user = getUser();
        $inputs = $args['input'];

        $countReward = Gold::whereUserId($user->id)->whereDate('created_at', today())->count('id');
        if ($countReward > 500) {
            return null;
        }

        //兼容老接口
        if (isset($inputs['video_id'])) {
            $video = Video::find($inputs['video_id']);

            if (is_null($video)) {
                throw new GQLException('视频不存在,请刷新后再试！');
            }

            $visited = $user->visitedArticles()->where('visited_id', $video->article->id)->first();
            if (!is_null($visited)) {
                return null;
            }

            // 判断用户最近15秒内有没有看视频，防止重刷
            if ($gold = $user->golds()->latest()->first()) {
                // dd($gold);
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
                $rewardGold = 333;
            } else if ($playDuration >= 30) {
                $rewardGold = 150;
            } else if ($playDuration >= 15) {
                $rewardGold = 70;
            }

            //观看失败 或 观看时长不足
            if ($rewardGold <= 0) {
                return null;
            }

            Visit::createVisit($user->id, $video->article->id, 'articles');

            $remark = sprintf('<%s>观看奖励', $video->id);
            // $gold   = Gold::makeIncome($user, $rewardGold, $remark);
            $user->goldWallet->changeGold($rewardGold, $remark);

            return $gold;
        }

        $video_ids = $inputs['video_ids'];
        $rewardGold = random_int(5, 10);

        //大致统计用户浏览历史
        foreach ($video_ids as $video_id) {
            Visit::createVisit($user->id, $video_id, 'videos');
        }

        $remark = sprintf('视频观看时长奖励');
        // $gold   = Gold::makeIncome($user, $rewardGold, $remark);
        $gold = $user->goldWallet->changeGold($rewardGold, $remark);

        return $gold;
    }

    public function queryDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $asos = Aso::where('group', '贡献来源')->where('name', '增加活跃值的场景')->orderBy("id")->get();

        $str = '';
        foreach ($asos as $aso) {
            $str = $str . $aso->value . PHP_EOL;
        }
        return '增加活跃值的场景:' . $str;
    }
}
