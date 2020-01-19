<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Ip;
use App\Profile;
use App\User;
use App\UserTask;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait UserResolvers
{
    public function me($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($user = getUser()) {

            //注意，前端headers 传的是 deviceHeaders.uniqueId = DeviceInfo.getUniqueID();
            $uuid = request()->header('uniqueId', null);
            if (!empty($uuid)) {
                // 修复旧版本无uuid
                if (empty($user->uuid)) {
                    $user->update(['uuid' => $uuid]);
                }
                // 手机系统升级UUID变更
                if (!empty($user->uuid) && $user->uuid !== $uuid) {
                    $user->update(['uuid' => $uuid]);
                }
            }
            return $user;
        }
    }

    public function resolveFriends($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user    = \App\User::findOrFail($args['user_id']);
        $follows = $user->followingUsers()->take(500)->get();
        $friends = [];
        foreach ($follows as $follow) {
            $friend = $follow->followed; //被关注的人
            $ffuids = $friend->followingUsers()->pluck('followed_id')->toArray();
            if (in_array($user->id, $ffuids)) {
                $friends[] = $friend;
            }
        }
        return $friends;
    }

    public function removeBlockUser($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        $user->blockUser($args['user_id']);
        return $user;
    }

    public function signIn($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $account = $args['account'] ?? $args['email'];
        $qb      = User::where('phone', $account)->orWhere('email', $account)->orWhere('account', $account);
        if ($qb->exists()) {
            $user = $qb->first();
            if (!password_verify($args['password'], $user->password)) {
                throw new GQLException('登录失败！账号或者密码不正确');
            }

            if ($user->status === User::STATUS_OFFLINE) {
                throw new GQLException('登录失败！账户已被封禁');
            } else if ($user->status === User::STATUS_DESTORY) {
                throw new GQLException('登录失败！账户已被注销');
            }

            $user->touch(); //更新用户的更新时间来统计日活用户
            return $user;
        } else {
            throw new GQLException('登录失败！邮箱或者密码不正确');
        }
    }

    public function signUp($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        if (isset($args['account'])) {

            $account = $args['account'];

            $exists = User::where('phone', $account)->orWhere('account', $account)->exists();
            //手机号格式验证
            $flag = preg_match('/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/', $account);
            if (!$flag) {
                throw new GQLException('注册失败，手机号格式不正确，请检查是否输入正确');
            }

            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $args['password'])) {
                throw GQLException('密码中不能包含中文');
            }

            if ($exists) {
                throw new GQLException('该账号已经存在');
            }
            $name = $args['name'] ?? User::DEFAULT_NAME;
            return self::createUser($name, $account, $args['password']);
        }

        $email  = $args['email'];
        $exists = User::Where('email', $email)->exists();

        if ($exists) {
            throw new GQLException('该邮箱已经存在');
        }

        $user        = self::createUser(User::DEFAULT_NAME, $email, $args['password']);
        $user->phone = null;
        $user->email = $email;
        $user->save();

        app_track_user("用户注册", 'register');

        Ip::createIpRecord('users', $user->id, $user->id);
        return $user;
    }

    public function signOut($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user_id = $args['user_id'];
        return \App\User::findOrFail($user_id);
    }

    public function resolveRecommendAuthors($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //TODO: 实现真正的个性推荐算法
        return self::latest('id');
    }

    public function resolveSearchUsers($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //TODO: 替换更好的scout search
        return self::where('name', 'like', '%' . $args['keyword'] . '%');
    }

    public function resolveNotifications($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user                = getUser();
        $notifications       = \App\Notification::where('notifiable_type', 'users')->where('notifiable_id', $user->id);
        $unreadNotifications = \App\Notification::where('notifiable_type', 'users')->where('notifiable_id', $user->id)->whereNull('read_at');
        switch ($args['type']) {
            case 'GROUP_COMMENT':
                $qb = $notifications->orderBy('created_at', 'desc')
                    ->whereIn('type', [
                        'App\Notifications\ReplyComment',
                        'App\Notifications\ArticleCommented',
                    ]);
                //mark as read
                $unread_notifications = $unreadNotifications
                    ->whereIn('type', [
                        'App\Notifications\ReplyComment',
                        'App\Notifications\ArticleCommented',
                    ])->get();
                $unread_notifications->markAsRead();
                break;
            case 'GROUP_OTHERS':
                $qb = $notifications->orderBy('created_at', 'desc')
                    ->whereIn('type', [
                        'App\Notifications\CollectionFollowed',
                        'App\Notifications\CategoryFollowed',
                        'App\Notifications\ArticleApproved',
                        'App\Notifications\ArticleRejected',
                        'App\Notifications\CommentAccepted',
                    ]);

                //mark as read
                $unread_notifications = $unreadNotifications
                    ->whereIn('type', [
                        'App\Notifications\CollectionFollowed',
                        'App\Notifications\CategoryFollowed',
                        'App\Notifications\ArticleApproved',
                        'App\Notifications\ArticleRejected',
                        'App\Notifications\CommentAccepted',
                    ])->get();
                $unread_notifications->markAsRead();
                break;
            case 'GROUP_LIKES':
                $qb = $notifications->orderBy('created_at', 'desc')
                    ->whereIn('type', [
                        'App\Notifications\ArticleLiked',
                        'App\Notifications\CommentLiked',
                    ]);
                //mark as read
                $unread_notifications = $unreadNotifications
                    ->whereIn('type', [
                        'App\Notifications\ArticleLiked',
                        'App\Notifications\CommentLiked',
                    ])->get();
                $unread_notifications->markAsRead();
                break;

            default:
                $qb = $notifications->orderBy('created_at', 'desc')->where('type', $args['type']);
                //mark as read
                $unread_notifications = $unreadNotifications->where('type', $args['type'])->get();
                $unread_notifications->markAsRead();
                break;
        }
        Cache::forget('unreads_' . $user->id);
        return $qb;
    }

    /**
     * 静默登录，uuid 必须传递，手机号可选
     */
    public function autoSignIn($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $qb = User::where('uuid', $args['uuid']);

        // 不是首次登录
        if ($qb->exists()) {
            $user = $qb->first();
            if ($user->status === User::STATUS_OFFLINE) {
                throw new GQLException('登录失败！账户已被封禁');
            } else if ($user->status === User::STATUS_DESTORY) {
                throw new GQLException('登录失败！账户已被注销');
            }
        } else {
            $user = User::create([
                'uuid'      => $args['uuid'],
                'account'   => $args['phone'] ?? $args['uuid'],
                'name'      => User::DEFAULT_NAME,
                'api_token' => Str::random(60),
                'avatar'    => User::AVATAR_DEFAULT,
            ]);
            Profile::create([
                'user_id'      => $user->id,
                'introduction' => '这个人暂时没有 freestyle ',
                'app_version'  => request()->header('version', null),
            ]);

            Ip::createIpRecord('users', $user->id, $user->id);
        }
        $user->updateProfileAppVersion($user);

        app_track_user('静默登录', "auto_signIn", $user->id);

        return $user;
    }

    public function updateUserInfo($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // 去除 lighthouse 自动传递的参数
        unset($args['directive']);

        if ($user = checkUser()) {
            if (isset($args['phone'])) {
                // 验证手机号
                $flag = preg_match('/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/', $args['phone']);
                if (!$flag) {
                    throw new GQLException('修改失败，手机号格式不正确，请检查是否输入正确');
                }

                // 查询是否已经存在
                $flag = User::where('phone', $args['phone'])
                    ->orWhere('account', $args['phone'])->exists();
                if ($flag) {
                    throw new GQLException('该手机号已被绑定，请检查是否输入正确');
                }
            }

            //TODO:暂时不牵涉前端的gql,后期需要修改掉的gql,有关用户信息修改的
            $args_profile_infos = ["age", "gender", "introduction", "birthday"];
            $profile_infos      = [];
            foreach ($args_profile_infos as $profile_info) {
                foreach ($args as $index => $value) {
                    if ($index == $profile_info) {
                        $profile_infos[$index] = $args[$index];
                        if ($index == "gender") {
                            $profile_infos[$index] = User::getGenderNumber($args[$index]);
                        }
                        if ($index == "birthday") {
                            if (Str::contains($args[$index], "1970-1-1")) {
                                $profile_infos[$index] = Carbon::parse($args[$index])->addHour(23)->addMinute(59)->addSecond(59);
                            }
                        }
                    }

                }
            }
            $user->update(array_diff($args, $profile_infos));
            if (!empty($profile_infos)) {
                $profile = $user->profile;

                $profile->update($profile_infos);
            }

            return $user;
        } else {
            throw new GQLException('未登录，请先登录！');
        }
    }

    public function destoryUserByToken($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($user = checkUser()) {
            $user->destoryUser();
            return true;
        }
        throw_if(!isset($user->id) || is_null($user), GQLException::class, '请登录后再尝试哦~');
    }

    //观看新手教程或采集视频教程任务状态变更
    public function newUserReword($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = checkUser();
        $type = $args['type'];
        if ($type === 'newUser') {
            $task             = \App\Task::where("name", "观看新手视频教程")->first();
            $userTask         = \App\UserTask::where("task_id", $task->id)->where("user_id", $user->id)->first();
            $userTask->status = \App\UserTask::TASK_REACH;
            $userTask->save();
            return 1;
        } else if ($type === 'douyin') {
            $task             = \App\Task::where("name", "观看采集视频教程")->first();
            $userTask         = \App\UserTask::where("task_id", $task->id)->where("user_id", $user->id)->first();
            $userTask->status = \App\UserTask::TASK_REACH;
            $userTask->save();
            return 1;
        }
        return -1;
    }

    public function bindDongdezhuan($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($user = checkUser()) {
            //不允许用户从APP手动指定绑懂得赚账户，必须默认本手机...
            $user->bindDDZ();
            return true;
        }
    }

    /**
     * @param $user
     */
    public function updateProfileAppVersion(User $user): void
    {
        if ($version = request()->header('version', null)) {
            $user->getProfileAttribute()->update([
                'app_version' => $version,
            ]);
        }
    }
}
