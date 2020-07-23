<?php

namespace Haxibiao\Question\Nova\Actions\Question;

use Haxibiao\Question\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Nova;

class SetGoldAndTicket extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = '调整智慧点及精力点';

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
        if ($fields->gold == "" || $fields->ticket == "") {
            return Action::danger('更新失败,至少设置一个值');
        }

        if ($qids = $models->pluck('id')) {
            $updateArr = [];

            if ($fields->gold >= 0) {
                $updateArr['gold'] = $fields->gold;
            };

            if ($fields->ticket > 0) {
                $updateArr['ticket'] = $fields->ticket;
            }

            if (count($updateArr)) {
                DB::beginTransaction(); //开启数据库事务
                try {
                    $rows = Question::whereIn('id', $qids)->update($updateArr);
                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollback();
                }
                return Action::message('操作成功,影响题目:' . $rows . '条');
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('智慧点', 'gold'),
            Number::make('精力点', 'ticket'),
        ];
    }
}
