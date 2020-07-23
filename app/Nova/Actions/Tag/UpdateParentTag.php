<?php

namespace Haxibiao\Question\Nova\Actions\Tag;

use Haxibiao\Question\Tag;
use Haxibiao\Question\Taggable;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;

class UpdateParentTag extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = '变更父类标签';

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
        $ids = $models->pluck('id')->toArray();
        foreach ($ids as $id) {
            $tag = Tag::find($id);
            $tag->tag_id = $fields->tag_id;
            $tag->save();
            Taggable::updateOrCreate([
                'tag_id' => $fields->tag_id,
                'taggable_id' => $id,
                'taggable_type' => 'tags',
            ]);
        }
        return Action::message('标签修改成功');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('分类', 'tag_id')->options(Tag::all()->pluck('name', 'id')),
        ];
    }
}
