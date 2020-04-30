<?php

namespace App\Traits\LiveRoom;

use App\Events\LiveRoom\CloseRoom;
use App\Events\LiveRoom\NewLiveRoomMessage;
use App\Events\LiveRoom\NewUserComeIn;
use App\Events\LiveRoom\UserGoOut;
use App\Exceptions\GQLException;
use App\LiveRoom;
use App\User;
use App\UserLive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Throwable;

trait LiveRoomResolvers
{
    /**
     * 创建直播室
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return LiveRoom|mixed
     * @throws GQLException
     * @throws Throwable
     */
    public function createLiveRoomResolver($root, array $args, $context, $info)
    {
        $user  = getUser();
        $title = Arr::get($args, 'title', null);
        $this->checkUser($user);
        throw_if(!$title, GQLException::class, '请输入直播间标题~');

        // 开过直播室,更新直播间信息即可
        if ($liveRoom = $user->liveRoom) {
            $liveRoom = LiveRoom::openLive($user, $liveRoom, $title);
        } else {
            // 创建直播室
            $liveRoom = LiveRoom::createLiveRoom($user, $title);
        }
        // 记录用户直播数据
        UserLive::recordLive($user, $liveRoom);
        return $liveRoom;
    }

    /**
     * 推荐直播间列表
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return LiveRoom|Builder
     */
    public function recommendLiveRoom($root, array $args, $context, $info)
    {
        return LiveRoom::whereStatus(self::STATUS_ON);
    }

    /**
     * 加入直播间
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return LiveRoom|LiveRoom[]|Collection|Model|mixed|null
     * @throws GQLException
     */
    public function joinLiveRoomResolver($root, array $args, $context, $info)
    {
        $user       = getUser();
        $liveRoomId = Arr::get($args, 'live_room_id', null);
        $liveRoom   = LiveRoom::find($liveRoomId);

        if ($userIds = Redis::get($liveRoom->redis_room_key)) {
            $userIds = json_decode($userIds, true);
            // 避免重复触发事件
            if (array_search($user->id, $userIds) === false) {
                event(new NewUserComeIn($user, $liveRoom));
            }
            return $liveRoom;
        }

        throw new GQLException('主播已经下播,下次早点来哦~');
    }

    /**
     * 发送弹幕
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return mixed
     */
    public function commentLiveRoomResolver($root, array $args, $context, $info)
    {
        $user         = getUser();
        $live_room_id = Arr::get($args, 'live_room_id', null);
        $message      = Arr::get($args, 'message', null);

        event(new NewLiveRoomMessage($user->id, $live_room_id, $message));
        return $message;
    }

    /**
     * 用户离开直播间
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return LiveRoom|LiveRoom[]|Collection|Model|mixed|null
     */
    public function leaveLiveRoomResolver($root, array $args, $context, $info)
    {
        $user         = getUser();
        $live_room_id = Arr::get($args, 'live_room_id', null);
        $room         = LiveRoom::find($live_room_id);

        if ($usersData = Redis::get($room->redis_room_key)) {
            $userIds = json_decode($usersData, true);

            // 保证不多给前端发通知
            if (array_search($user->id, $userIds) !== false) {
                event(new UserGoOut($user, $room));
            }
        }

        return $room;
    }

    /**
     * 主播关闭直播间
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return bool
     * @throws GQLException
     */
    public function closeLiveRoomResolver($root, array $args, $context, $info)
    {
        $live_room_id = Arr::get($args, 'live_room_id', null);
        $user         = getUser();
        $room         = LiveRoom::find($live_room_id);
        if ($user->id !== $room->streamer->id) {
            throw new GQLException('关闭直播失败,您没有权限关闭直播哦~');
        }
        self::closeRoom($room);
        optional($room->userLive)->recordLiveDuration($room);
        return true;
    }

    /**
     * 主播获取当前直播间观众列表
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return User[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|null
     */
    public function getLiveRoomUsers($root, array $args, $context, $info)
    {
        $live_room_id = Arr::get($args, 'live_room_id', null);
        $room         = LiveRoom::find($live_room_id);
        $users_key    = Redis::get($room->redis_room_key);
        if (!$users_key) {
            return null;
        }

        $userIds = json_decode($users_key, true);
        // 去掉主播自己
        $userIds = array_diff($userIds, array($room->streamer->id));
        return User::whereIn('id', $userIds)->get();
    }

    /**
     * 直播间异常（断流）
     * @param $root
     * @param array $args
     * @param $context
     * @param $info
     * @return bool
     */
    public function ExceptionLiveRoomResolver($root, array $args, $context, $info)
    {
        $live_room_id = Arr::get($args, 'live_room_id', null);
        $room         = LiveRoom::find($live_room_id);
        $room->increment('count_exception');
        // 两名观众监测了异常，直接关闭
        if ($room->count_exception >= 1 && $room->status === self::STATUS_ON) {
            self::closeRoom($room);
        }
        return true;
    }

    /**
     * 检测用户是否有有资格开启直播
     * @param User $user
     * @throws GQLException
     */
    public function checkUser(User $user)
    {
        if (in_array($user->status, [User::STATUS_OFFLINE], true)) {
            throw new GQLException('您没有开启直播的权限哦~');
        }
    }
}
