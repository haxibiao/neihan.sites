<?php

namespace App\Nova\Actions;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Techouse\SelectAutoComplete\SelectAutoComplete;

class TransferCollection extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '分配给马甲号';

    /**
     *
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //马甲号们
        $vestId = $fields->vest_id;
        foreach ($models as $collection) {
            $posts = $collection->posts;
            $collection->user_id = $vestId;
            //将合集内视频也分配到对应马甲号上去
            foreach ($posts as $post) {
                $oldUserId = $post->owner_id ?: $post->user_id;
                $post->owner_id = $oldUserId;
                $post->user_id = $vestId;
                $post->saveDataOnly();
            }
            $collection->save();

        }
        return Action::message('分配成功');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $data = \App\User::query()
            ->where('role_id', User::VEST_STATUS)
            ->pluck('name', 'id')
            ->toArray();
        return [
            SelectAutoComplete::make(_("选择马甲账户"), 'vest_id')->options(
                $data
            ),
        ];
    }
}
