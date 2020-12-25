<?php

namespace App;

use App\Exceptions\UserException;
use App\Traits\Shareable;
use GraphQL\Type\Definition\ResolveInfo;
use Haxibiao\Media\Video as BaseVideo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Video extends BaseVideo
{
    use Shareable;
    /**
     * 获取视频下载链接
     */
    public function downloadVideo($rootValue, $args, $context, $resolveInfo)
    {
        $videoId   = data_get($args, 'video_id');
        $video     = Video::findOrFail($videoId);
        $user      = getUser();
        $originUrl = $video->path;

        // 之前下载过,不需要重复解析
        $share = Share::where('user_id', $user->id)
            ->where('shareable_id', $video->id)
            ->where('shareable_type', 'videos')
            ->where('active', true)
            ->first();
        if ($share) {
            return $share->url;
        }

        $share = Share::buildFor($video)
            ->setActive(false)
            ->setUrl($originUrl)
            ->setUserId($user->id)
            ->build();

        // 请求处理media.haxibiao.com进行MetaData处理
        $uuid           = $share->uuid;
        $title2MetaData = sprintf('uuid:%s', $uuid);
        $curl           = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "http://media.haxibiao.com/api/video/modifyMetadata",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => [
                'title' => $title2MetaData,
                'url'   => $originUrl,
            ],
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);

        $responseCode = data_get($result, 'code');
        $modifiedUrl  = data_get($result, 'data.MediaUrl');
        if ($responseCode == 200 && $modifiedUrl) {
            $share->url    = $modifiedUrl;
            $share->active = true;
            $share->save();

            return $modifiedUrl;
        }
        throw new UserException('下载失败');
    }

    //刷视频悬浮球奖励
    public static function videoPlayReward($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user   = getUser();
        $inputs = $args['input'];

        //今日通过 视频刷获取的积分
        $countReward = Gold::whereUserId($user->id)
            ->where('remark', 'like', '%视频观看时长奖励%')
            ->whereBetWeen('created_at', [today(), today()->addDay()])->sum('gold');
        if (Gold::TODAY_VIDEO_PLAY_MAX_GOLD < $countReward) {
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
            $gold   = $user->goldWallet->changeGold($rewardGold, $remark);

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
