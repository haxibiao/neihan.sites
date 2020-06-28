<?php

namespace App\Nova\Actions\User;

use App\Contribute;
use App\Gold;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Nova;

class RewardUser extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = '奖励用户';

    public function uriKey()
    {
        return str_slug(Nova::humanize($this));
    }

    const REMARK = '系统奖励';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            if (!is_null($fields->wallet)) {
                Transaction::makeIncome($user->wallet, $fields->wallet, self::REMARK);
            }
            if (!is_null($fields->gold)) {
                Gold::makeIncome($user, $fields->gold, self::REMARK);
            }
            if (!is_null($fields->contribute)) {
                Contribute::rewardUserContribute($user->id,
                    Contribute::REWARD_VIDEO_CONTRIBUTED_ID,
                    $fields->contribute, Contribute::REWARD_VIDEO_CONTRIBUTED_TYPE, "系统奖励");
            }

            //DZ-615 TODO:这里需要给用户发送奖励通知
        }

        return Action::message('成功影响用户:' . count($models));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('智慧点', 'gold'),
            Number::make('余额', 'wallet')->step(0.01),
            Number::make('贡献点', 'contribute'),
        ];
    }
}
