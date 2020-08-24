<?php

namespace App\Traits;

use App\InvitationPhase;
use App\Transaction;
use App\UserInvitation;
use App\Wallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait UserInvitationRepo
{
    public static function getOrCreate($user_id)
    {
        $userInvitation = UserInvitation::firstOrNew(['user_id' => $user_id]);
        if (!isset($userInvitation->id)) {
            $userInvitation->phase_id = InvitationPhase::DEFAULT_PHASE_ID;
            $userInvitation->save();
        } else {
            //检测一下是否到达下一个阶段
            if (!is_null(InvitationPhase::reward($userInvitation))) {
                $userInvitation->refresh();
            }
        }

        return $userInvitation;
    }

    public function getUnionWallet()
    {
        $wallet = $this->unionWallet;
        if (is_null($wallet)) {
            $wallet = Wallet::firstOrCreate(['user_id' => $this->user_id, 'type' => Wallet::UNION_WALLET]);
        }

        return $wallet;
    }

    public function syncData($isSave = false)
    {
        $this->firends_count         = $this->invitations()->count();
        $this->success_firends_count = $this->invitations()->success()->count();

        if ($isSave) {
            $this->save();
        }

        return $this;
    }

    public function getRedPacket()
    {
        $wallet = $this->redPacket;
        if (is_null($wallet)) {
            $wallet = Wallet::firstOrCreate(['user_id' => $this->user_id, 'type' => Wallet::RED_PACKET_WALLET]);
        }

        return $wallet;
    }

    public function getRedPacketPhase()
    {
        $amount = $this->getRedPacketPhaseAmount();
        $phases = collect(UserInvitation::getRedPacketPhases());
        return $phases->firstWhere('amount', $amount);
    }

    public static function getRedPacketPhases()
    {
        return [
            [
                'amount'            => 20,
                'invitations_count' => 15,
            ],
            [
                'amount'            => 50,
                'invitations_count' => 30,
            ],
            [
                'amount'            => 100,
                'invitations_count' => 50,
            ],

        ];
    }

    public function setRedPacketPhaseAmount($amount)
    {
        $this->red_packet_phase_amount = $amount;
        $this->save();

        return $this->red_packet_phase_amount;
    }

    public function getRedPacketPhaseAmount()
    {
        $amount = $this->red_packet_phase_amount;
        $phases = collect(UserInvitation::getRedPacketPhases());
        if ($amount <= 0) {
            //设置起始默认阶段金额
            $phase  = $phases->firstWhere('invitations_count', '>', $this->success_firends_count);
            $amount = Arr::get($phase, 'amount', 0);
            if ($amount > 0) {
                $this->setRedPacketPhaseAmount($amount);
            }
        }

        return $amount;
    }

    public static function nextRedPacketPhase($amount)
    {
        $phases = collect(UserInvitation::getRedPacketPhases());
        return $phases->firstWhere('amount', '>', $amount);
    }

    public function updateNextRedPacketPhase()
    {
        $amount = $this->getRedPacketPhaseAmount();
        $phase  = UserInvitation::nextRedPacketPhase($amount);
        if (is_array($phase)) {
            //金额足够
            if ($phase['amount'] > $amount) {
                $amount = $phase['amount'];
                $this->setRedPacketPhaseAmount($amount);
            }
        }

        return $amount;
    }

    public function redPacketUp($amount)
    {
        $redPacket = $this->getRedPacket();
        $user      = $this->user;

        //更新下一阶段 && 转换金币
        DB::beginTransaction();
        try {
            //将红包的钱转入现金钱包
            $redPacket->transfer($user->wallet, $amount, '拆红包奖励收益');
            //更新下个进度
            $this->updateNextRedPacketPhase();

            DB::commit();
            $isUpSuccess = true;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollback();
            $isUpSuccess = false;
        }

        return $isUpSuccess;
    }

    public function rewardInviterRedPacket()
    {
        $isPhaseUp             = false;
        $redPacket             = $this->getRedPacket();
        $phase                 = $this->getRedPacketPhase();
        $balance               = $redPacket->balance;
        $phaseAmount           = Arr::get($phase, 'amount', 0);
        $phaseInvitationsCount = Arr::get($phase, 'invitations_count', 0);
        $count                 = $this->red_packet_invites_count;

        if ($phaseAmount > 0) {
            $redPacketAmount = 0;
            //20元档位
            if ($phaseAmount == 20 && $phaseInvitationsCount == 15) {
                $redPacketAmount = UserInvitation::randomRedPacketAmount($phaseAmount, $phaseInvitationsCount, 5, $count);
            }

            //50元档位
            if ($phaseAmount == 50 && $phaseInvitationsCount == 30) {
                $redPacketAmount = UserInvitation::randomRedPacketAmount($phaseAmount, $phaseInvitationsCount, 10, $count);
            }

            //100元档位
            if ($phaseAmount == 100 && $phaseInvitationsCount == 50) {
                $redPacketAmount = UserInvitation::randomRedPacketAmount($phaseAmount, $phaseInvitationsCount, 20, $count);
            }

            //红包金额溢出 && 补充剩下金额
            if (($balance + $redPacketAmount) >= $phaseAmount) {
                $redPacketAmount = $phaseAmount - $balance;
                $isPhaseUp       = true;
            }

            //发放红包到钱包中
            if ($redPacketAmount > 0) {
                Transaction::makeIncome($redPacket, $redPacketAmount, '好友助力红包');
                //阶段红包升级
                if ($isPhaseUp) {
                    //重新计算邀请人数
                    $this->update(['red_packet_invites_count' => 0]);
                    //红包升级
                    $this->redPacketUp($phaseAmount);
                } else {
                    $this->increment('red_packet_invites_count');
                }

            }

        }

    }

    /**
     * 随机红包金额(上下浮动0.01- 0.09)
     *
     * @param float $totalAmount
     * @param integer $totalCount
     * @param integer $topCount
     * @param integer $currentCount
     * @return 金额
     */
    public static function randomRedPacketAmount(float $totalAmount, int $totalCount, int $topCount, int $currentCount)
    {
        if ($currentCount < $topCount) {
            $amount = $totalAmount / 2 / $topCount;
            $amount = mt_rand_float($amount, $amount + 0.09);
        } else {
            $amount = $totalAmount / 2 / ($totalCount - $topCount);
            $amount = round($amount, 2);
        }

        return $amount;
    }
}
