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

class QuestionRank extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = '设置权重';

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
        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                //调整权重必须刷新 review_id 避免新区间大部分用户已排重这个 review_id
                if ($fields->rank > $model->rank) {
                    //提升,就到最前排
                    $model->review_id = Question::max('review_id') + 1;
                } else if ($fields->rank < $model->rank) {
                    //降低就到最后排
                    $model->review_id = Question::min('review_id') - 1;
                }
                $model->rank = $fields->rank;
                $model->save();
                $lastmodel = $model;
            }

            //更新分类的权重数组
            if (isset($lastmodel)) {
                $lastmodel->category->updateRanks();
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return Action::danger('修改失败!' . $e->getMessage());
        }
        \DB::commit();
        return Action::message('修改成功!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('设置题目权重', 'rank')->options(
                [
                    11 => '权重11：例如,用户待审核题,默认',
                    10 => '权重10：',
                    9  => '权重9：',
                    8  => '权重8：例如,内部的图片题,默认',
                    7  => '权重7：例如,用户的图片题,默认',
                    6  => '权重6：例如,内部的文字题,默认',
                    5  => '权重5：例如,用户的文字题,默认',
                    4  => '权重4：例如,内部的视频题,默认',
                    3  => '权重3：例如,用户的视频题,默认',
                    2  => '权重2：例如,用户自定义',
                    1  => '权重1：例如,用户自定义',
                    0  => '权重0：例如,用户自定义',
                ]
            ),
        ];
    }
}
