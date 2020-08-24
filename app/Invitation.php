<?php

namespace App;

use App\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use Traits\InvitationResolvers;
    use Traits\InvitationRepo;
    use Traits\InvitationAttrsCache;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'invited_in',
        'be_inviter_id',
        'app_id',
        'today_rewards_count',
        'secondary_user_ids',
    ];

    protected $casts = [
        'invited_in'         => 'datetime',
        'secondary_user_ids' => 'array',
    ];

    const NEW_USER_REWARD = 0.2;

    //今日最大奖励次数上限
    const TODAY_MAX_REWARDS_COUNT = 20;

    //每日最大邀请数量
    const DAILY_MAX_INVITE_COUNT = 90;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function app()
    // {
    //     return $this->belongsTo(App::class);
    // }

    public function beInviter()
    {
        return $this->belongsTo(User::class, 'be_inviter_id');
    }

    public function secondaryInvitations()
    {
        return $this->hasMany(Invitation::class, 'user_id', 'be_inviter_id');
    }

    public function scopeFilters($query, $args)
    {
        $filters   = Arr::get($args, 'filters', []);
        $invitedIn = Arr::get($filters, 'invited_in');

        if ($invitedIn == 'ALL') {
            $query->success();
        } else {
            foreach ($filters as $key => $value) {
                $query->where($key, $value);
            }
        }

        return $query;
    }

    public function scopeUnSuccess($query)
    {
        return $query->whereNull('invited_in');
    }

    public function scopeSuccess($query)
    {
        return $query->whereNotNull('invited_in');
    }
}
