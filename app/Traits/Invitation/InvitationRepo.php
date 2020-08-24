<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Invitation;
use App\InvitationReward;
use App\Transaction;
use App\UserInvitation;

trait InvitationRepo
{
    /**
     * 创建邀请
     *
     * @param ID $userId
     * @param ID $beInviterId
     * @return Invitation
     */
    public static function createInvitation($userId, $beInviterId)
    {
        throw_if($userId == $beInviterId, GQLException::class, '绑定失败,邀请人不能和被邀请人相同!');

        //邀请到了上限
        if (Invitation::todayInviteUpperLimit($userId)) {
            return null;
        }

        //生成一个厂长APP id
        $appId = 1;

        //创建邀请记录 && 分配厂长
        $invitation = Invitation::withTrashed()->firstOrCreate([
            'user_id'       => $userId,
            'be_inviter_id' => $beInviterId,
        ], ['app_id' => $appId]);

        //恢复软删除过的
        if ($invitation->trashed()) {
            $invitation->restore();
        }

        //修复24小时内先注册绑定支付宝、后绑定邀请口令的用户,无法算邀请成功
        // $wallet = data_get($invitation, 'beInviter.wallet');
        // if (!is_null($wallet)) {
        //     $payId = $wallet->getPayId(Withdraw::ALIPAY_PLATFORM);
        //     if (!empty($payId)) {
        //         $invitation->reward();
        //     }
        // }

        //同步下下级用户
        $invitation->syncSecondaryUser(true);

        //当下级超过上级数量的时候 || 下降数量超过20个后 自动解除上级
        $invitation->clearInvitation();

        return $invitation;
    }

    /**
     * 清除邀请关系
     *
     * @return boolean
     */
    public function clearInvitation()
    {
        $userId     = $this->user_id;
        $isDeleted  = false;
        $invitation = Invitation::hasBeInvitation($userId);
        if (!is_null($invitation)) {
            //下级
            $userInvitation = UserInvitation::getOrCreate($userId);
            //上级
            $superiorInvitation = UserInvitation::getOrCreate($invitation->user_id);
            //当前下级数量超过上级数量的时候 || 下级数量超过20个后 自动解除上级
            $isDeleted = $userInvitation->success_firends_count >= 20 || $userInvitation->success_firends_count > $superiorInvitation->success_firends_count;

            if ($isDeleted) {
                $invitation->delete();
            }
        }

        return $isDeleted;
    }

    public static function encode($id)
    {
        return base64_encode($id);
    }

    public static function decode($code)
    {
        return base64_decode($code);
    }

    public static function hasBeInvitation($id)
    {
        return Invitation::Where('be_inviter_id', $id)->first();
    }

    public function isInviteSuccess()
    {
        return !empty($this->invited_in);
    }

    public function reward()
    {
        //TODO:该操作可以异步处理,有大量SQL查询和读写
        if (!$this->isInviteSuccess()) {
            //发放邀请奖励
            $this->rewardBeInviter();
            // $this->rewardInviter();
        }
    }

    public function rewardBeInviter()
    {
        //被邀请人奖励0.2元到现金钱包
        $reward   = Invitation::NEW_USER_REWARD;
        $source   = 'new_user_reward';
        $rewarded = InvitationReward::invitationId($this->id)
            ->source($source)
            ->userId($this->be_inviter_id)
            ->first();
        if (is_null($rewarded)) {
            //注意：此处是需要获得被邀请人用户现金钱包
            $wallet = data_get($this, 'beInviter.wallet');
            if (!is_null($wallet)) {
                // $transaction      = Transaction::makeIncome($wallet, $reward, '新人福利', '奖励');
                // $invitationReward = InvitationReward::makeReward($this, $transaction, $source);
                // return $invitationReward;
            }
        }
    }

    public function rewardInviter()
    {
        //邀请人奖励0.2元到现金钱包
        $reward   = Invitation::NEW_USER_REWARD;
        $source   = 'invite_new_user_reward';
        $rewarded = InvitationReward::invitationId($this->id)
            ->source($source)
            ->userId($this->user_id)
            ->first();
        //需要未领取过奖励
        if (is_null($rewarded)) {
            //注意:此处是需要获得现金钱包
            $wallet = data_get($this, 'user.wallet');
            if (!is_null($wallet)) {
                $transaction      = Transaction::makeIncome($wallet, $reward, '邀请成功', '奖励');
                $invitationReward = InvitationReward::makeReward($this, $transaction, $source);

                return $invitationReward;
            }
        }
    }

    public function complete()
    {
        $this->invited_in = now();
        $this->save();
        //更新邀请信息
        $inviter = UserInvitation::getOrCreate($this->user_id);
        //2020年02月13日由于大量注册小号刷拆红包奖励,暂时关闭:发放拆红包奖励
        // $inviter->rewardInviterRedPacket();
        $inviter->syncData(true);

        return $this;
    }

    public function syncSecondaryUser($isSave = false)
    {
        $user = $this->user;
        //找到我的邀请记录
        $invitation = $user->inviteMe;

        if (!is_null($invitation)) {
            $uids = $user->invitations()
                ->success()
                ->select('be_inviter_id')
                ->get()
                ->pluck('be_inviter_id')
                ->toArray();
            //保存我的下级ID
            if (count($uids) > 0) {
                $invitation->secondary_user_ids = $uids;
                $invitation->isSave($isSave);

                return $invitation->secondary_user_ids;
            }
        }
    }

    public function clearSecondaryUser($isSave = false)
    {
        $this->secondary_user_ids = [];
        $this->isSave($isSave);

        return $this->secondary_user_ids;
    }

    public function isSave($isSave = false)
    {
        if ($this->isDirty() && $isSave) {
            $this->save();
        }

        return $this;
    }

    public static function todayInvitationsCount($userId)
    {
        return Invitation::today()->where('user_id', $userId)->count();
    }

    public static function todayInviteUpperLimit($userId)
    {
        $count = Invitation::todayInvitationsCount($userId);
        return $count > Invitation::DAILY_MAX_INVITE_COUNT;
    }
}
