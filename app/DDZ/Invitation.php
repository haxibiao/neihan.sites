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

    public function getBonusRate()
    {
        $rate           = 1;
        $userInvitation = UserInvitation::userId($this->user_id)->first();
        if (!is_null($userInvitation)) {
            $phases = Invitation::getBonusPhases();
            $key    = sprintf('%s.rate', $userInvitation->phase_id);
            $rate   = Arr::get($phases, $key, 1);
        }

        return $rate;
    }

    public static function getBonusPhases()
    {
        return [
            '1'  => ['rate' => 1.0],
            '2'  => ['rate' => 1.1],
            '3'  => ['rate' => 1.2],
            '4'  => ['rate' => 1.3],
            '5'  => ['rate' => 1.4],
            '6'  => ['rate' => 1.5],
            '7'  => ['rate' => 1.6],
            '8'  => ['rate' => 1.7],
            '9'  => ['rate' => 1.8],
            '10' => ['rate' => 2.0],
        ];
    }

    public function bonus(float $rewardAmount)
    {
        //计算奖励收益
        $rewardAmount = $this->calculateReward($rewardAmount);
        if ($rewardAmount >= 0.01) {
            //这个收益是翻倍收益
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
        $rate = $this->getBonusRate();
        //分红的加速倍率，1+才有效

        $rewardAmount = 0.05;
        $rewardAmount = $rate > 1 ? $rewardAmount * $rate : $rewardAmount;
        //一天20次后奖励下调到0.01
        $rewardAmount = $this->isMaxUper() ? 0.01 : $rewardAmount;

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
