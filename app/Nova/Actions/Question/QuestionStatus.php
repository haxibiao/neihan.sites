<?php

namespace Haxibiao\Question\Nova\Actions\Question;

use Haxibiao\Question\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;

class QuestionStatus extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = '状态变更';

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
        if (!isset($fields->submit)) {
            return Action::danger('状态不能为空');
        }
        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                $model->submit     = $fields->submit;
                $model->timestamps = true;
                $model->save();
                $lastmodel = $model;
            }

            //更新分类的权重数组
            if (isset($lastmodel)) {
                $lastmodel->category->updateRanks();
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
            Select::make('状态', 'submit')->options(Question::getSubmitStatus()),
        ];
    }
}
