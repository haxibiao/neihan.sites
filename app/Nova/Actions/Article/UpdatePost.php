<?php

namespace App\Nova\Actions\Article;

use Laravel\Nova\Nova;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Collection as AppCollection;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Techouse\SelectAutoComplete\SelectAutoComplete;

class UpdatePost extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels, Actionable;

    public $name = '变更合集';
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
        if (!isset($fields->tag1) && !isset($fields->status) && !isset($fields->new_collection)) {
            return Action::danger('状态或者合集或者创建合集名称不能为空！');
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

                if (isset($fields->tag1)) {
                    $tag = AppCollection::find($fields->tag1);
                } else if (isset($fields->new_collection)) {
                    $tag = AppCollection::firstOrNew(["name" => $fields->new_collection]);
                    if (empty($tag->id)) {
                        $tag->description = "暂无简介";
                        $tag->user_id = getUser()->id;
                        $tag->status = AppCollection::STATUS_ONLINE;
                        $tag->save();
                    }
                }
                if ($tag) {
                    $model->collectivize([$tag->id]);
                    $tag->increment('count');
                }
                // if (isset($fields->tag2)) {
                //     $tag = AppCollection::find($fields->tag2);
                //     if ($tag) {
                //         $model->collectivize([$tag->id]);
                //         $tag->increment('count');
                //     }
                // }
                // if (isset($fields->tag3)) {
                //     $tag = AppCollection::find($fields->tag3);
                //     if ($tag) {
                //         $model->collectivize([$tag->id]);
                //         $tag->increment('count');
                //     }
                // }
            }
            // $model->timestamp = true;
            $model->save();
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
        $data =  \App\Collection::query()->orderBy('count', 'DESC')->pluck('name', 'id')->toArray();
        return [
            Select::make('状态', 'status')->options(
                [
                    1  => '公开',
                    0  => '草稿',
                    -1 => '下架',
                ]
            ),

            SelectAutoComplete::make("合集", 'tag1')->options(
                $data
            ),

            Text::make("创建合集", 'new_collection')
            // SelectAutoComplete::make("合集2", 'tag2')->options(
            //     $data
            // ),

            // SelectAutoComplete::make("合集2", 'tag3')->options(
            //     $data
            // ),
        ];
    }
}
