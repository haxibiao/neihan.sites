<?php

namespace App;

use App\Invitation;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationReward extends Model
{
    protected $fillable = [
        'user_id',
        'invitation_id',
        'source',
        'reward',
        'reward_type',
        'reward_id',
    ];

    const FIRST_INVITATION_REWARD = 1;
    const INVITATION_REWARD       = 0.5;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    public function scopeSource($query, $value)
    {
        return $query->where('source', $value);
    }

    public function scopeUserId($query, $value)
    {
        return $query->where('user_id', $value);
    }

    public function scopeInvitationId($query, $value)
    {
        return $query->where('user_id', $value);
    }

    public static function firstReward(Invitation $invitation)
    {
        $inviter = $invitation->user;
        if ($inviter->invitations_success_count == 0) {
            $inviterWallet = $inviter->wallet;
            $reward        = InvitationReward::FIRST_INVITATION_REWARD;
            $transaction   = Transaction::makeIncome($inviterWallet, $reward, '首次邀请奖励');
            InvitationReward::makeReward($invitation, $transaction, 'first_invitation_reward');
        }
    }

    public static function makeReward(Invitation $invitation, $rewardObj, string $source)
    {
        $reward = 0;
        if ($rewardObj instanceof Transaction) {
            $reward = $rewardObj->amount;
        }

        $invitationReward = InvitationReward::create([
            'user_id'       => $invitation->user_id,
            'invitation_id' => $invitation->id,
            'source'        => $source,
            'reward'        => $reward,
            'reward_type'   => $rewardObj->getMorphClass(),
            'reward_id'     => $rewardObj->id,
        ]);

        return $invitationReward;
    }

    public static function withdrawShareBonus(Withdraw $withdraw)
    {
        $beInviterWallet = $withdraw->wallet;

        //防止脏数据,钱包不存在
        if (blank($beInviterWallet)) {
            return null;
        }

        $invitation = Invitation::hasBeInvitation($beInviterWallet->user_id);
        //当前用户存在被邀请关系
        if (!is_null($invitation)) {
            $inviter = $invitation->user;
            if (!is_null($inviter)) {
                $wallet = $inviter->wallet;
                //分成奖励20%
                $shareBonusAmount = number_format($withdraw->amount * 0.2, 2);
                $transaction      = Transaction::makeIncome($wallet, $shareBonusAmount, '邀请好友提现分成');
                InvitationReward::makeReward($invitation, $transaction, 'be_invited_user_withdraw');
            }
        }
    }

}
