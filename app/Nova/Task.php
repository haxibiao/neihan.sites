<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
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
        'id', 'name', 'details',
    ];

    public static $group = '任务管理';

    public static function label()
    {
        return '任务';
    }

    public static function singularLabel()
    {
        return '任务';
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
            Code::make('奖励', 'reward')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE)->withMeta(
                [
                    'value' => json_encode(['gold' => '0', 'contribute' => '0']),
                ]
            ),
            Code::make('任务配置', 'resolve')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            //Code::make('解析', 'resolve')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            BelongsTo::make('任务模版', 'reviewFlow', 'App\Nova\ReviewFlow')->hideWhenUpdating(),
            Number::make('最多完成的次数（每日任务用）', 'max_count')->withMeta(
                [
                    'value' => 0,
                ]
            ),
            DateTime::make('开始时间', 'start_at'),
            DateTime::make('截止时间', 'end_at'),
            DateTime::make('创建时间', 'created_at')->exceptOnForms(),
            Image::make('任务图标', 'icon')->store(
                function (Request $request, $model) {
                    $file = $request->file('icon');
                    return $model->saveDownloadImage($file);
                })->thumbnail(function () {
                return $this->icon_url;
            })->preview(function () {
                return $this->icon_url;
            }),

            Image::make('任务背景图', 'background_img')->store(
                function (Request $request, $model) {
                    $file = $request->file('background_img');
                    return $model->saveBackGroundImage($file);
                })->thumbnail(function () {
                return $this->background_img;
            })->preview(function () {
                return $this->background_img;
            })->disableDownload(),
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
        return [
            new \App\Nova\Filters\Other\TaskType,
            new \App\Nova\Filters\Other\TaskStatusType,
        ];
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
