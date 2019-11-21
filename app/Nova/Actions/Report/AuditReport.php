<?php

namespace App\Nova\Actions\Report;

use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;

class AuditReport extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '一键审核';

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
        $status = $fields->status;

        if ($status == '') {
            return Action::danger('操作失败,请先选中状态!');
        }

        foreach ($models as $model) {
            $reportable = $model->reportable;

            $model->status = Report::SUCCESS_STATUS;
            $model->save();

            if ($status == Report::SUCCESS_STATUS) {
                $model->reportSuccess();
            }
        }

        return Action::message('操作成功');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('状态', 'status')->options(Report::getStatuses()),
        ];
    }
}
