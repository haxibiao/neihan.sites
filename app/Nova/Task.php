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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('任务名称', 'name'),
            Text::make('任务描述', 'details'),
            BelongsTo::make('任务模版', 'review_flow', 'App\Nova\ReviewFlow'),
            Select::make('目标行为', 'task_action')
                ->options(\App\Task::getActions())
                ->displayUsingLabels()
                ->hideFromIndex(),
            Select::make('目标类', 'relation_class')
                ->options(\App\Task::getActionClasses())
                ->displayUsingLabels()
                ->hideFromIndex(),
            Code::make('目标对象', 'task_object')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE)->hideFromIndex(),
            Select::make('类型', 'type')->options(\App\Task::getTypes())->displayUsingLabels(),
            Select::make('状态', 'status')->options(\App\Task::getStatuses())->displayUsingLabels(),
            Select::make('任务分组', 'group')->hideFromIndex()
                ->options([
                    '新人任务' => '新人任务',
                    '每日任务' => '每日任务',
                    '自定义任务' => '自定义任务',
                    '实时任务' => '实时任务',
                    '每周任务' => '每周任务',
                ])
                ->displayUsingLabels(),
            Code::make('奖励', 'reward')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            Code::make('任务配置', 'resolve')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            //Code::make('解析', 'resolve')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            Number::make('最多完成的次数（每日任务用）', 'max_count'),
            DateTime::make('创建时间', 'created_at')->exceptOnForms()->hideFromIndex(),
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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\Task\TaskType,
            new Filters\Task\TaskStatus,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
