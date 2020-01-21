<?php

namespace App\DDZ;

use DDZUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Invitation extends Model
{
    use SoftDeletes;

    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'user_id',
        'invited_in',
        'be_inviter_id',
        'today_rewards_count',
    ];

    protected $casts = [
        'invited_in' => 'datetime',
    ];

    //今日最大奖励次数上限
    const TODAY_MAX_REWARDS_COUNT = 20;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beInviter()
    {
        return $this->belongsTo(User::class, 'be_inviter_id');
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

    public function isInviteComplete()
    {
        return !empty($this->invited_in);
    }

    public function getTitleAttribute()
    {
        $arrs = [
            '虎', '狼', '鼠', '鹿', '貂', '猴', '貘',
            '树', '懒', '斑', '马', '狗', '狐', '熊',
            '象', '豹', '牛',
        ];

        $title = Arr::random($arrs) . '厂';

        return $title;
    }

    public function scopeFilters($query, $args)
    {
        $filters = Arr::get($args, 'filters', []);
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query;
    }

    public function isMaxUper()
    {
        //FIXME: 可以计算的时候参考updated_at时间跨度today() 自动重置计数，减少对定时任务的依赖..
        return $this->today_rewards_count >= Invitation::TODAY_MAX_REWARDS_COUNT;
    }

    //返回当前分红的倍率
    public static function getBonusRate()
    {
        //TODO：先简单返回，去掉InvitationPhase表，换一个数组，代码更新即可
        return 1;
    }

    public function bonus(float $rewardAmount)
    {
        //计算奖励收益
        $rewardAmount = $this->calculateReward($rewardAmount);
        if ($rewardAmount >= 0.01) {
            $this->rewardInviter($rewardAmount, '好友贡献奖励');

            //邀请人再向他的上级分红奖励 && 随机奖励0.01-0.02
            $rewardAmount = Arr::random([0.01, 0.02]);
            $this->rewardSuperior($rewardAmount, '扩散好友分红奖励');
        }
    }

    public function rewardInviter(float $rewardAmount, string $remark)
    {
        $inviter = $this->user;
        if (!is_null($inviter)) {
            //邀请人发放奖励金额 && 更新每日奖励次数
            DDZUser::makeInvitationReawrd($inviter, $rewardAmount, $remark);
            $this->increment('today_rewards_count');
        }
    }

    public function calculateReward($reward)
    {
        //分红倍率
        $rate = Invitation::getBonusRate();
        //分红的加速倍率，1+才有效
        if ($rate >= 1) {
            $reward *= $rate;
        }

        //一天20次后奖励下调到0.01
        $rewardAmount = $this->isMaxUper() ? 0.01 : 0.05;

        return $rewardAmount;
    }

    public function rewardSuperior(float $rewardAmount, string $remark)
    {
        $superiorInvitation = Invitation::hasBeInvitation($this->user_id);
        //存在上上级邀请关系
        if (!is_null($superiorInvitation)) {

            //交叉关系,互相为上下级,不发放奖励
            if ($superiorInvitation->user_id == $this->be_inviter_id) {
                return null;
            }

            $superior = $superiorInvitation->user;
            if (!is_null($superior)) {
                //上上级分红
                DDZUser::makeInvitationReawrd($superior, $rewardAmount, $remark);
            }
        }
    }

}
