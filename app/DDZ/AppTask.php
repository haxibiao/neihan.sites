<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//同步工厂APP里的Task情况到工厂去
class AppTask extends Model
{

    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'user_id',
        'left_golds',
    ];

    public function save(array $options = array())
    {
        // 隔日重置所有任务完成计数
        if (today() > $this->updated_at) {
            $this->done_reward1 = 0;
            $this->done_reward2 = 0;
            $this->done_reward3 = 0;
        }
        $hasModified = false;
        //同步余额等信息
        if ($user = getUser(false)) {
            $this->left_rmb     = $user->wallet->available_balance;
            $this->total_income = $user->wallet->total_withdraw_amount;

            // left_rmb or total_income  has changed
            if ($this->isDirty(['left_rmb', 'total_income'])) {
                $hasModified = true;
            }
        }
        parent::save($options);

        if ($hasModified) {
            $user                          = $this->user;
            $profile                       = $user->profile;
            $successfulWithdraws           = $user->appTasks()->sum('total_income');
            $availableWithdraws            = $user->appTasks()->sum('left_rmb');
            $profile->successful_withdraws = $successfulWithdraws;
            $profile->avaliable_withdraws  = $availableWithdraws;
            $profile->save();
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

}
