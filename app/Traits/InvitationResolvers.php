<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Invitation;
use App\User;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait InvitationResolvers
{
    public function resolveInvitation($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user   = getUser();
        $userId = $user->id;

        //验证邀请口令
        $inviterId = Invitation::validateSlogan(Arr::get($args, 'invite_slogan'));
        //验证邀请者
        $inviter = Invitation::valadateUser($inviterId);

        //复制到自己答口令不返还结果
        if ($user->id == $inviter->id) {
            return null;
        }

        //是否被邀请
        throw_if(!is_null(Invitation::hasBeInvitation($userId)), GQLException::class, '绑定失败,您已被邀请过了!');
        //是否注册24小时内
        $deadLine = $user->created_at->addDays(1);
        throw_if(now() > $deadLine, GQLException::class, '邀请绑定失败,您的注册时间已超过24小时!');
        throw_if($inviter->invitations()->today()->count() > 5, GQLException::class, '您的上级今日小弟数量太多了,换个上级吧!');
        $lastInvitation = $inviter->invitations()->today()->latest('id')->first();
        if (!is_null($lastInvitation)) {
            throw_if(now()->diffInRealMinutes($lastInvitation->created_at) <= 1, GQLException::class, '邀请失败,请稍后再试试!');
        }

        //创建邀请记录
        $invitation = Invitation::createInvitation($inviterId, $userId);

        //发放奖励给被邀请人
        $invitation->rewardBeInviter();

        //前置绑定支付宝检测
        $wallet = $user->wallet;
        if (!is_null($wallet)) {
            $isBindPayId = $wallet->getPayId(Withdraw::ALIPAY_PLATFORM) || $wallet->getPayId(Withdraw::WECHAT_PLATFORM);
            if ($isBindPayId && !$invitation->isInviteSuccess()) {
                $invitation->rewardInviter();
                $invitation->complete();
            }
        }

        return $invitation;
    }

    public function resolveInvitations($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = User::find($args['user_id']);
        throw_if(is_null($user), GQLException::class, '参数错误,用户不存在!!!');

        return $user->invitations()->latest('id');
    }

    public function resolveMyInviter($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = User::find($args['user_id']);
        if (!is_null($user)) {
            return $user->myInviter;
        }
    }

    public function resolveDeleteInvitation($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        throw_if(!isset($args['user_id']) && !isset($args['id']), GQLException::class, '解除失败,参数错误');
        $user   = getUser();
        $userId = $user->id;

        //根据参数构建不同的对象
        if (isset($args['id'])) {
            $invitation = Invitation::find($args['id']);
        } else {
            $invitation = Invitation::where('user_id', $args['user_id'])->where('be_inviter_id', $userId)->first();
        }

        throw_if(is_null($invitation), GQLException::class, '解除失败,邀请关系已不存在！！！');

        throw_if(now() <= $invitation->created_at->addDays(7), GQLException::class, '邀请关系绑定7天内不可辞职！');

        throw_if(!in_array($userId, [
            'user_id'       => $invitation->user_id,
            'be_inviter_id' => $invitation->be_inviter_id,
        ]), GQLException::class, '解除失败,权限不足!!!');

        $invitation->delete();

        return $invitation;
    }

    /**
     * 验证邀请口令
     *
     * @param string $slogan
     * @return string
     * @throws GQLException::class
     */
    public static function validateSlogan($slogan)
    {
        if (is_numeric($slogan)) {
            $code = $slogan;
        } else {
            //匹配邀请码
            preg_match_all('#【(.*?)】#', $slogan, $match);
            $code = Arr::get($match, '1.0');
            throw_if(empty($code), GQLException::class, '邀请口令有误,无法正常识别!');
        }

        //新版邀请码是纯数字00001 && 此处保留兼容老邀请码base64
        $id = is_numeric($code) ? $code : Invitation::decode($code);
        //验证邀请口令
        throw_if(!is_numeric($id), GQLException::class, '邀请口令不正确,请复制正确的邀请口令!');

        return $id;
    }

    /**
     * 验证用户
     *
     * @param ID $id
     * @return User
     * @throws GQLException::class
     */
    public static function valadateUser($id)
    {
        $user = User::find($id);
        throw_if(is_null($user), GQLException::class, '邀请用户不存在,请复制正确的邀请口令!');

        return $user;
    }

    public function resolveSecondaryInvitations($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $limit       = Arr::get($args, 'limit', 10);
        $invitations = [];
        $user        = User::find($args['user_id']);

        $beInviterIds = $user->invitations()
            ->success()
            ->select('be_inviter_id')
            ->get()
            ->pluck('be_inviter_id');

        if (count($beInviterIds) > 0) {
            $invitations = Invitation::latest('id')->whereIn('user_id', $beInviterIds)->take($limit)->get();
        }

        return $invitations;
    }

    public function resolveMyInvitations($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $limit       = Arr::get($args, 'limit', 18);
        $user        = getUser();
        $data        = [];
        $invitations = $user->invitations()->latest('id')->get();
        foreach ($invitations as $invitation) {
            $data[] = $invitation;
        }
        $surplusCount = $limit - $invitations->count();

        if ($surplusCount > 0) {
            // $apps = App::select('name')->factory()->take($surplusCount)->get();
            //填充未满18个的
            for ($i = 0; $i < $surplusCount; $i++) {
                $invitation              = new Invitation;
                $invitation->id          = random_str(7);
                $invitation->faker_title = 'Faker Title';
                $data[]                  = $invitation;
            }
        }

        return $data;

    }

    public function resolveTitle(Invitation $invitation)
    {
        // $field = isset($invitation->faker_title) ? 'faker_title' : 'title';

        // return $invitation->$field;

    }
}
