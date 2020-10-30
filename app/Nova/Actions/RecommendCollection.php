<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use App\Collection as AppCollection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;

class RecommendCollection extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '加入推荐列表';

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //将选中的集合加入推荐列表
        foreach ($models as $collection) {
            if ($collection->sort_rank > 0) {
                $collection->update(['sort_rank' => 0]);
            } else {
                $collection->update(['sort_rank' => AppCollection::RECOMMEND_COLLECTION]);
            }
        }
        return Action::message('推荐成功');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
        Text::make('提示')->default('已推荐集合会被取消推荐'),
    ];
    }
}
