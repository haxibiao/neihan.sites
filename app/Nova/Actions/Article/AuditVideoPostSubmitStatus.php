<?php

namespace App\Nova\Actions\Article;

use App\Contribute;
use App\Gold;
use App\Jobs\AwardResolution;
use App\Notifications\ReceiveAward;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Nova;

class AuditVideoPostSubmitStatus extends Action
{
    use InteractsWithQueue, Queueable;

    const REWARD_GOLD = 10;

    const AUDIT_TEMPLATE = [
        1 => '涉及广告',
        2 => '视频模糊不清',
        3 => '低质量内容',
        4 => '低俗色情',
        5 => '无法播放',
        6 => '多重水印',
    ];

    public $name = '审核';

    public function uriKey()
    {
        return Str::slug(Nova::humanize($this));
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
        $submit = $fields->submit;
        $remark = null;
        if ($fields->reason_text) {
            $remark = $fields->reason_text;
        } else if ($fields->reason_select) {
            $remark = self::AUDIT_TEMPLATE[$fields->reason_select];
        }
        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                //跳过
                if (!in_array($model->type, ['post', 'issue'])) {
                    continue;
                }
                //跳过已经审核成功的视频动态
                if ($model->submit == \App\Article::SUBMITTED_SUBMIT) {
                    continue;
                }
                $model->remark = $remark;
                //通过审核
                if ($submit > 0) {

                    //发布视频动态奖励
                    $user = $model->user;
                    $user->notify(new ReceiveAward('发布视频动态奖励', self::REWARD_GOLD, $user, $model->id));
                    Gold::makeIncome($user, self::REWARD_GOLD, '发布视频动态奖励');

                    $model->submit = \App\Article::SUBMITTED_SUBMIT;
                    $model->save(['timestamps' => false]);

                    //付费问答奖励平分
                    if ($model->type == 'issue') {
                        //平分奖励
                        AwardResolution::dispatch($model->issue)
                            ->delay(now()->addDays(7));
                    }

                    Action::message('审批结果:' . '已通过,奖励通知已执行');
                } else {
                    $model->submit = \App\Article::REFUSED_SUBMIT;
                    $model->save(['timestamps' => false]);
                    //问答审核
                    if ($model->type == 'issue') {
                        $issue = $model->issue;
                        $user  = $issue->user;
                        Gold::makeIncome($user, $issue->gold, '问答审核不通过,金币退回!');
                    }
                    Action::danger('审批结果:' . '已拒绝问答');
                }
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return Action::danger('审批失败!' . $e->getMessage());
        }
        \DB::commit();
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('审核结果', 'submit')->options(
                [
                    1  => '同意',
                    -1 => '拒绝',
                ]
            ),
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
