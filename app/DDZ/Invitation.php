<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Invitation extends Model
{
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
        return $this->today_rewards_count >= Invitation::TODAY_MAX_REWARDS_COUNT;
    }

}
