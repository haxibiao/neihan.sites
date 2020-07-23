<?php

namespace Haxibiao\Question\Nova\Actions\Curation;



use App\Gold;
use Haxibiao\Question\Curation;
use Haxibiao\Question\Notifications\CurationRewardNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Nova;

class AuditCuration extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    const AUDIT_TEMPLATE = [
        1 => '题目无误',
        2 => '描述不符',
    ];

    public $name = '一键审核';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $isAgree = $fields->status == Curation::SUCCESS_STATUS;

        $remark = null;
        if ($fields->reason_text) {
            $remark = $fields->reason_text;
        } else if ($fields->reason_select) {
            $remark = self::AUDIT_TEMPLATE[$fields->reason_select];
        }
        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                $isSubmited = $model->status != Curation::REVIEW_STATUS;

                //已经收录的问题则跳过审核
                if ($isSubmited) {
                    continue;
                }

                if ($isAgree) {

                    $model->status       = Curation::SUCCESS_STATUS;
                    $model->gold_awarded = Curation::REWARD_GOLD;
                    $model->save();
                    $user = $model->user;
                    //纠题奖励
                    Gold::makeIncome($user, Curation::REWARD_GOLD, '纠题奖励' . $model->id);
                    $user->notify(new CurationRewardNotification($model));
                } else {

                    $model->status = Curation::FAILED_STATUS;
                    $model->remark = $remark;
                    $model->save();
                }
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return Action::danger('审批失败!');
        }
        \DB::commit();
        return Action::message('审批成功!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('审核结果', 'status')->options(Curation::getStatuses()),
            Select::make('审核意见模板(可选)', 'reason_select')->options(
                self::AUDIT_TEMPLATE
            ),
            Textarea::make('审核意见(可选)', 'reason_text')->withMeta(['extraAttributes' => [
                'placeholder' => '没有合适的模板试试自定义吧~'
            ],]),
        ];
    }

    /**
     * @Author      XXM
     * @DateTime    2019-02-15
     * @description [Nova支持中文]
     * @return      [type]        [description]
     */
    public function uriKey()
    {
        return str_slug(Nova::humanize($this));
    }
}
