<?php

namespace App\DDZ;

use App\DDZ\User;
use App\DDZ\Wallet;
use App\DDZ\Invitation;
use App\DDZ\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'user_id',
        'firends_total_contribute',
        'success_firends_count',
        'phase_id',
        'rate',
        'next_increment_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phase()
    {
        return $this->belongsTo(InvitationPhase::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'user_id', 'user_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id')->where('type', Wallet::UNION_WALLET);
    }

    public function getTodayIncomeAttribute()
    {
        $inCome = 0;
        $wallet = $this->wallet;
        if (!is_null($wallet)) {
            $transaction = Transaction::where('wallet_id', $wallet->id)->whereDate('created_at', today())->latest('id')->first();
            $inCome      = data_get($transaction, 'balance', 0);
        }

        return $inCome;
    }

    public function getPreRewardAmountAttribute()
    {
        $count = $this->invitations()->whereNull('invited_in')->count();

        return $count * 1.2;
    }

    public function getInviteCodeAttribute()
    {
        return Invitation::encode($this->id);
    }

    public function getRateAttribute()
    {
        $rate = Arr::get($this->attributes, 'rate', 0);
        //倍率
        if ($rate <= 0) {
            $phase = $this->phase;
            if (!is_null($phase)) {
                $rate       = $phase->rate;
                $this->rate = $rate;
            }
        }

        return (float) $rate;
    }

    public function getNextIncrementAttribute()
    {
        $time      = $this->next_increment_at;
        $timestamp = is_null($time) ? 0 : $time->timestamp;

        $data = [
            'next_increment_at'           => $time,
            'next_increment_at_timestamp' => $timestamp,
        ];

        return $data;
    }
}
