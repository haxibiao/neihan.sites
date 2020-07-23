<?php

namespace Haxibiao\Question\Nova\Actions\Question;

use Haxibiao\Question\QuestionRecommend;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Nova;

class RecommendQuestion extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '推荐题目';

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
        if (!isset($fields->rank)) {
            return Action::danger('权重不能为空');
        }
        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                // 避免重复创建
                QuestionRecommend::firstOrCreate([
                    'question_id' => $model->id,
                    'rank'        => $fields->rank,
                ]);
            }
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
            \DB::rollBack();
            return Action::danger('批量变更失败,数据回滚...');
        }
        \DB::commit();
        return Action::message('变更成功!成功影响' . count($models) . '条数据');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('推荐权重', 'rank'),
        ];
    }
}
