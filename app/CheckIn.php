<?php

namespace App;

use App\Traits\CheckInFacade;
use App\Traits\CheckInRepo;
use App\Traits\CheckInResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckIn extends Model
{
    use CheckInResolvers;
    use CheckInRepo;
    use CheckInFacade;

    protected $fillable = [
        'user_id',
        'created_at',
        'updated_at',
        'gold_reward',
        'contribute_reward',
        'reward_rate',
        'keep_checkin_days',
    ];

    //最大签到天数
    const MAX_SIGNIN_DAYS = 7;

    const CONTRIBUTE_REWARD = 18;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //scope

    public function scopeUserId($query, $value)
    {
        return $query->where('user_id', $value);
    }

    //attrs

    public function getDateAttribute()
    {
        return $this->created_at->toDateString();
    }

    public function getYearAttribute()
    {
        return $this->created_at->year;
    }

    public function getMonthAttribute()
    {
        return $this->created_at->month;
    }

    public function getDayAttribute()
    {
        return $this->created_at->day;
    }

    public function getSignedAttribute()
    {
        return isset($this->id) ? true : false;
    }

    //static method

    public static function todaySigned($userId)
    {
        return CheckIn::where('user_id', $userId)->where('created_at', '>=', today())->first();
    }

    public static function yesterdaySigned($userId)
    {
        return CheckIn::userId($userId)
            ->whereBetween('created_at', [today()->subDay(), today()])
            ->first();
    }
}
