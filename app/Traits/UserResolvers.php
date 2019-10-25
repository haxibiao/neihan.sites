<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\User;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait UserResolvers
{
    public function me($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return getUser();
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
            if ($user->status != User::STATUS_ONLINE) {
                throw new GQLException('登录失败！账户已被封禁');
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
            $exists  = User::where('phone', $account)->orWhere('account', $account)->exists();
            if ($exists) {
                throw new GQLException('该账号已经存在');
            }
            return self::createUser(User::NAME_DEFAULT, $account, $args['password']);
        }

        $email  = $args['email'];
        $exists = User::Where('email', $email)->exists();

        if ($exists) {
            throw new GQLException('该邮箱已经存在');
        }

        $user        = self::createUser(User::NAME_DEFAULT, $email, $args['password']);
        $user->phone = null;
        $user->email = $email;
        $user->save();

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

        $phone = null;
        if (isset($args['phone']) && $args['phone'] != '') {
            $phone = $args['phone'];
        }

        // 不是首次登录
        if ($qb->exists()) {
            $user = $qb->first();
            if ($user->status != User::STATUS_ONLINE) {
                throw new Exception('登录失败！账户已被封禁');
            }
            if (!is_null($phone)) {
                $user->update(['phone' => $phone]);
            }
            return $user;
        }
        $avatar_formatter = 'http://cos.' . env('APP_NAME') . '.com/storage/avatar/avatar-%d.jpg';
        $avatar           = sprintf($avatar_formatter, rand(1, 15));
        $user             = User::create([
            'uuid'         => $args['uuid'],
            'account'      => $args['phone'] ?? $args['uuid'],
            'name'         => '匿名墨友',
            'api_token'    => str_random(60),
            'avatar'       => $avatar,
            'introduction' => '这个人暂时没有 freestyle ',
            'phone'        => $phone,
        ]);

        $action = \App\Action::create([
            'user_id'         => $user->id,
            'actionable_type' => 'users',
            'actionable_id'   => $user->id,
        ]);

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
                $flag = User::where('phone', $args['phone'])->exists();
                if ($flag) {
                    throw new GQLException('该手机号已被绑定，请检查是否输入正确');
                }
            }
            $user->update($args);
            return $user;
        } else {
            throw new GQLException('未登录，请先登录！');
        }
    }
}