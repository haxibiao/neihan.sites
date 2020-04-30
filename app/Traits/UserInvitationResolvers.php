<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\User;
use App\UserInvitation;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait UserInvitationResolvers
{
    public function resolveUserInvitation($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (isset($args['user_id'])) {
            $user = User::find($args['user_id']);
        } else {
            $user = getUser();
        }

        throw_if(is_null($user), GQLException::class, '用户信息不存在!');
        $userInvitation = UserInvitation::getOrCreate($user->id);

        return $userInvitation;
    }

    public function resolveInrementRateAd($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userInvitation = $this->resolveUserInvitation($rootValue, $args, $context, $resolveInfo);
        $userInvitation->rate += 0.01;
        $userInvitation->next_increment_at = now()->addHour(3);
        $userInvitation->save();

        return $userInvitation;
    }
    public function resolveInvitationRewardGold($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        if ($user->count_success_invitation >= 5) {
            // TODO: 从Task中取到 Reward Gold
            return 600;
        }
        return 0;

    }
    public function resolveRedPacket($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = checkUser();
        if (!is_null($user)) {
            $userInvitation = UserInvitation::getOrCreate($user->id);
            $redPacket      = $userInvitation->getRedPacket();

            if ($redPacket->totalIncome >= 170) {
                return null;
            }

            $balance    = $redPacket->balance;
            $userAmount = $userInvitation->getRedPacketPhaseAmount();
            if ($balance > 0 && $balance >= $userAmount) {
                //红包升级
                $userInvitation->redPacketUp($userAmount);
                $userAmount = $userInvitation->getRedPacketPhaseAmount();
                $balance    = $redPacket->balance;
            }

            $differenceAmount = $userAmount - $balance;
            $phase            = $userInvitation->getRedPacketPhase();
            $phaseAmount      = Arr::get($phase, 'amount');
            $progress         = $balance > 0 ? round($balance / $phaseAmount, 2) : 0;

            return [
                'phase_amount'             => $phaseAmount,
                'balance'                  => number_format($balance, 2),
                'progress'                 => $progress,
                'difference_amount'        => number_format($differenceAmount, 2),
                'red_packet_invites_count' => $userInvitation->red_packet_invites_count,
            ];
        }
    }
}
