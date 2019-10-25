<?php

namespace App\Nova\Actions\Article;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;

class UpdateArticle extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels, Actionable;

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
        if (!isset($fields->type) and !isset($fields->status)) {
            return Action::danger('状态或者类型不能为空');
        }

        \DB::beginTransaction();
        try {
            foreach ($models as $model) {
                if (isset($fields->status)) {
                    $model->status = $fields->status;
                    //如果下架，相应的视频应该也要下架
                    if ($fields->status == -1) {
                        if ($model->type == 'video') {
                            $video         = $model->video;
                            $video->status = -1;
                            $video->save();
                        }
                    }
                }
                if (isset($fields->type)) {
                    $model->type = $fields->type;
                }
                // $model->timestamp = true;
                $model->save();
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \DB::rollBack();
            return Action::danger('数据批量变更失败，数据回滚');
        }
        DB::commit();

        return Action::message('修改成功!,成功修改掉' . count($models) . '条数据');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('状态', 'status')->options(
                [
                    1  => '公开',
                    0  => '草稿',
                    -1 => '下架',
                ]
            ),
            Select::make('类型', 'type')->options(
                [
                    'video'   => '视频',
                    'article' => '文章',
                    'post'    => '动态',
                ]
            ),
        ];
    }
}
