<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Version extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Version';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * 指示资源是否是全局可搜索的。
     *
     * @var bool
     */
    public static $globallySearchable = false;

    public static function label()
    {
        return '版本';
    }

    public static $group = '系统管理';

    public static $parent = null;

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
            Text::make('版本', 'name'),
            Number::make('build'),
            Text::make('包名', 'package'),
            Textarea::make('更新描述', 'description'),
            Number::make('大小', 'size')->step(0.01)->displayUsing(function ($value) {
                return formatBytes($value);
            }),
            Select::make('系统', 'os')->options($this::getOses()),
            Select::make('状态', 'status')->options($this::getStatuses())->onlyOnForms(),
            Status::make('状态', function () {
                return $this->getStatusToChinese();
            })->loadingWhen(['运行中'])
                ->failedWhen([
                    '已下架',
                    '已删除',
                ]),
            Select::make('类型', 'type')->options($this::getTypes())->displayUsingLabels(),
            Boolean::make('是否强制更新', 'is_force'),
            Url::make('下载地址', 'url')->label('下载')->clickableOnIndex(),
            DateTime::make('发布时间', 'created_at')->exceptOnForms(),
            DateTime::make('更新时间', 'updated_at')->hideWhenCreating(),
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
