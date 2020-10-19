<?php

namespace App\Nova\Actions\Article;

use App\Collection as AppCollection;
use App\Tag;
use Laravel\Nova\Nova;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        if (!isset($fields->tag1) && !isset($fields->status) && !isset($fields->tag3) && !isset($fields->tag2)) {
            return Action::danger('状态或者合集不能为空！');
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
                    if ($tag) {
                        $model->collectivize([$tag->id]);
                        $tag->increment('count');
                    }
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

            SelectAutoComplete::make("合集1", 'tag1')->options(
                $data
            ),

            // SelectAutoComplete::make("合集2", 'tag2')->options(
            //     $data
            // ),

            // SelectAutoComplete::make("合集2", 'tag3')->options(
            //     $data
            // ),
        ];
    }
}
