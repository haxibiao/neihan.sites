<?php

namespace Haxibiao\Question\Nova\Actions\Curation;

use Haxibiao\Question\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Nova;

class CurationQuestionRemove extends Action
{
    public $name = '批量移除题';

    use InteractsWithQueue, Queueable, SerializesModels;

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
        $ids = $models->pluck('question_id')->toArray();

        $result = Question::whereIn('id', $ids)->update(['submit' => Question::REMOVED_SUBMIT]);

        return Action::message('批量移除成功(' . $result . ')');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
