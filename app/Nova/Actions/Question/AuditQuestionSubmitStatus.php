<?php

namespace Haxibiao\Question\Nova\Actions\Question;


use App\Exceptions\UserException;
use Haxibiao\Question\Events\PublishQuestion;
use Haxibiao\Question\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Nova;

class AuditQuestionSubmitStatus extends Action
{
    const REWARD_GOLD = 10;

    const AUDIT_TEMPLATE = [
        1 => '答案有误',
        2 => '涉及广告',
        3 => '违反出题规则',
        4 => '题目具有主观性',
        5 => '已有相同题目',
        6 => '题干描述不清',
    ];

    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = '审核';

    public function uriKey()
    {
        return str_slug(Nova::humanize($this));
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $statusOfAgree = $fields->status;
        $remark        = null;
        if ($fields->reason_text) {
            $remark = $fields->reason_text;
        } else if ($fields->reason_select) {
            $remark = self::AUDIT_TEMPLATE[$fields->reason_select];
        }
        $result = "";
        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                if ($statusOfAgree > 0) {
                    $isFirstApprove = $model->submit == \App\Question::REVIEW_SUBMIT;
                    $model->update([
                        'submit'      => Question::SUBMITTED_SUBMIT,
                        'remark'      => $remark,
                        'rank'        => $fields->rank ?? $model->getDefaultRank(),
                        'review_id'   => Question::max('review_id') + 1,
                        'reviewed_at' => now(),
                    ]);
                    $model->timestamps = true;
                    $result            = '已通过';
                    if ($isFirstApprove) {
                        //出题成功奖励
                        event(new PublishQuestion($model));
                        $model->is_rewarded = 1;
                        $result             = '已通过,奖励通知已执行';
                    }
                    $model->save();
                } else {
                    $model->update([
                        'submit'      => $statusOfAgree,
                        'remark'      => $remark,
                        'rank'        => $fields->rank ?? $model->getDefaultRank(),
                        'rejected_at' => now(),
                    ]);
                    $model->timestamps = true;
                    $model->save();
                    $result = $statusOfAgree == -2 ? '已拒绝题目' : "已移除题目";
                    $this->markAsFailed($model, new UserException($result));
                    $lastmodel = $model;
                }

                //更新分类统计和权重区间
                if (isset($lastmodel)) {
                    $category = $model->category;
                    if ($category) {
                        $category->questions_count = $category->questions()->count();
                        $category->updateRanks();
                    }
                }
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return Action::danger('审批失败!' . $e->getMessage());
        }
        \DB::commit();

        if ($statusOfAgree > 0) {
            return Action::message('审批结果:' . $result);
        } else {
            return Action::danger('审批结果:' . $result);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('审核结果', 'status')->options(
                [
                    1  => '同意(有通知)',
                    -1 => '移除',
                    -2 => '拒绝',
                ]
            ),
            Text::make('权重', 'rank')->help('请分配权重1-4,无手动指定将按照默认权重分配'),
            Select::make('审核意见模板(可选)', 'reason_select')->options(
                self::AUDIT_TEMPLATE
            ),
            Textarea::make('审核意见(可选)', 'reason_text')->withMeta([
                'extraAttributes' => [
                    'placeholder' => '没有合适的模板试试自定义吧~',
                ],
            ]),
        ];
    }
}
