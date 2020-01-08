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
    ];

    public function save(array $options = array())
    {
        // 隔日重置所有任务完成计数
        if (today() > $this->updated_at) {
            $this->done_reward1 = 0;
            $this->done_reward2 = 0;
            $this->done_reward3 = 0;
        }
        //同步余额等信息
        if ($user = getUser(false)) {
            $this->left_golds   = $user->gold_balance;
            $this->left_rmb     = $user->available_balance;
            $this->total_income = $user->success_withdraw_sum_amount;
        }

        parent::save($options);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    //methods

    //统计当日喝水任务次数
    public static function countHeshuiTaskDone(\App\User $user, $count)
    {
        $ddzUser     = $user->getDongdezhuanUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->done_reward1 = $count;
            $appTask->save();
            \info("用户 $user->id,  $user->name, uuid: $user->uuid,  appTask里 $app_name_cn 在懂得赚,喝水任务次数更新成功, 2.0工厂面板可见...");
        } else {
            // \info("用户 $user->id, uuid: $user->uuid,  appTask里 $app_name_cn 在懂得赚没找到...");
        }
    }

    //统计当日睡觉任务次数
    public static function countShuijiaoTaskDone(\App\User $user, $count)
    {
        $ddzUser     = $user->getDongdezhuanUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->done_reward2 = $count;
            $appTask->save();
            \info("用户 $user->id, $user->name, uuid: $user->uuid,  appTask里 $app_name_cn 在懂得赚,睡觉任务次数更新成功, 2.0工厂面板可见...");
        } else {
            // \info("用户  $user->id, uuid: $user->uuid,  appTask里 $app_name_cn 在懂得赚没找到...");
        }
    }

    //统计当日看视频次数
    public static function countRewardVideoDone(\App\User $user, $count)
    {
        $ddzUser     = $user->getDongdezhuanUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->done_reward3 = $count;
            $appTask->save();
            \info("用户 $user->id,  $user->name, uuid: $user->uuid,  appTask里 $app_name_cn 在懂得赚,看视频任务次数更新成功, 2.0工厂面板可见...");
        } else {
            // \info("用户  $user->id, uuid: $user->uuid,  appTask里 $app_name_cn 在懂得赚没找到...");
        }
    }

}
