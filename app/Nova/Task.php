<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Task extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Task';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return '任务';
    }

    public static function singularLabel()
    {
        return '内容管理';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('任务名称', 'name'),
            Text::make('任务描述', 'details'),
            Select::make('类型', 'type')->options(\App\Task::getTypes())->displayUsingLabels(),
            Select::make('状态', 'status')->options(\App\Task::getStatuses())->displayUsingLabels(),
            Code::make('奖励', 'reward')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            // Number::make('统计', count),
            // check_functions
            Code::make('解析', 'resolve')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            DateTime::make('开始时间', 'start_at'),
            DateTime::make('截止时间', 'end_at'),
            DateTime::make('创建时间', 'created_at')->exceptOnForms(),
            // DateTime::make('截止时间', 'updated_at'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
